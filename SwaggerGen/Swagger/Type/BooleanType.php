<?php

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;

/**
 * Basic boolean type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class BooleanType extends AbstractType
{

    const REGEX_DEFAULT = '(?:=(true|false|1|0))?';

    private $default = null;

    /**
     * @throws Exception
     */
    protected function parseDefinition($definition)
    {
        $match = array();
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable boolean definition: '{$definition}'");
        }

        if (strtolower($match[1]) !== 'boolean') {
            throw new Exception("Not a boolean: '{$definition}'");
        }

        if (isset($match[2])) {
            $this->default = ($match[2] === '1') || (strtolower($match[2]) === 'true');
        }
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
            if (!in_array($data, array('0', '1', 'true', 'false'))) {
                throw new Exception("Invalid boolean default: '{$data}'");
            }
            $this->default = ($data == '1') || (strtolower($data) === 'true');
            return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray()
    {
        return self::arrayFilterNull(array_merge(array(
            'type' => 'boolean',
            'default' => $this->default,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
