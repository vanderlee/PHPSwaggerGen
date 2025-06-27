<?php

namespace SwaggerGen\Swagger\Type;

use DateTime;
use DateTimeInterface;
use SwaggerGen\Exception;

/**
 * Special form of strings type definition which handles date formats.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class DateType extends AbstractType
{

    /** @noinspection PhpRegExpUnsupportedModifierInspection */
    const REGEX_DEFAULT = '(?:=(\S+))?';

    /**
     * Map of recognized format names to Swagger formats
     * @var array
     */
    private static $formats = array(
        'date' => 'date',
        'date-time' => 'date-time',
        'datetime' => 'date-time',
    );
    private static $datetime_formats = array(
        'date' => 'Y-m-d',
        'date-time' => DateTimeInterface::RFC3339,
    );

    /**
     * @var String
     */
    private $format;

    /**
     * @var DateTime
     */
    private $default = null;

    /**
     * @throws Exception
     */
    protected function parseDefinition($definition)
    {
        $match = array();
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable date definition: '{$definition}'");
        }

        $type = strtolower($match[1]);

        if (!isset(self::$formats[$type])) {
            throw new Exception("Not a date: '{$definition}'");
        }
        $this->format = self::$formats[$type];

        $this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
    }

    /**
     * @param string $command The comment command
     * @param string $data Any data added after the command
     * @return AbstractType|boolean
     * @throws Exception
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        if (strtolower($command) === 'default') {
            $this->default = $this->validateDefault($data);
            return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'type' => 'string',
            'format' => $this->format,
            'default' => $this->default ? $this->default->format(self::$datetime_formats[$this->format]) : null,
        ), parent::toArray()));
    }

    /**
     * @throws Exception
     */
    private function validateDefault($value)
    {
        if (empty($value)) {
            throw new Exception("Empty date default");
        }

        if (($DateTime = date_create($value)) !== false) {
            return $DateTime;
        }

        throw new Exception("Invalid '{$this->format}' default: '{$value}'");
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
