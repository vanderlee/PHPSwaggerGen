<?php

namespace SwaggerGen\Swagger\Type\Custom;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\AbstractObject;
use SwaggerGen\Swagger\Type\StringType;

/**
 * Mac type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class MacType extends StringType implements ICustomType
{

    const PATTERN = '^([0-9A-F]){2}(:[0-9A-F]{2}){5}$';

    /**
     * List of formats recognized by this class
     * @var string[]
     */
    private static $formats = array('mac');

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

    public static function getFormats()
    {
        return self::$formats;
    }

    public static function setFormats(array $formats)
    {
        self::$formats = $formats;
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

        $match = [];
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable MAC definition: '{$definition}'");
        }

        if (!in_array(strtolower($match[1]), self::$formats)) {
            throw new Exception("Not a MAC: '{$definition}'");
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
            throw new Exception("Empty MAC default");
        }

        if (preg_match('/^([0-9A-F]){2}(:[0-9A-F]{2}){5}$/', $value) !== 1) {
            throw new Exception("Invalid MAC default value: '{$value}'");
        }

        return $value;
    }

}
