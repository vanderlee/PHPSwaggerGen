<?php

namespace SwaggerGen\Swagger\Type\Custom;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\AbstractObject;
use SwaggerGen\Swagger\Type\StringType;

/**
 * Email type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class EmailType extends StringType implements ICustomType
{

    const PATTERN = '\A[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\z';

    /**
     * List of formats recognized by this class
     * @var string[]
     */
    private static $formats = array('email');

    /**
     * Construct and set up the regular expression for this type
     *
     * @param AbstractObject $parent
     * @param string $definition
     */
    public function __construct(AbstractObject $parent, $definition)
    {
        $this->pattern = self::PATTERN;

        parent::__construct($parent, $definition);
    }

    /**
     * Parse a type definition string, assuming it belongs to this type
     *
     * @param string $definition
     * @throws Exception
     */
    protected function parseDefinition($definition)
    {
        $definition = self::trim($definition);

        $match = array();
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable email definition: '{$definition}'");
        }

        if (!in_array(strtolower($match[1]), self::$formats)) {
            throw new Exception("Not an email: '{$definition}'");
        }

        $this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
    }

    /**
     * Check (and optionally reformat) a default value
     *
     * @param string $value
     * @return string
     * @throws Exception
     */
    protected function validateDefault($value)
    {
        if (empty($value)) {
            throw new Exception("Empty email default");
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception("Invalid email default value: '{$value}'");
        }

        return $value;
    }

    public static function getFormats()
    {
        return self::$formats;
    }

    public static function setFormats(array $formats)
    {
        self::$formats = $formats;
    }

}
