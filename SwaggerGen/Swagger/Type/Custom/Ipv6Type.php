<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger\Type\Custom;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\AbstractObject;
use SwaggerGen\Swagger\Type\StringType;

/**
 * IPv6 type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Ipv6Type extends StringType implements ICustomType
{

    public const PATTERN = '(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))';

    /**
     * List of formats recognized by this class
     *
     * @var string[]
     */
    private static $formats = [
        'ipv6',
    ];

    /**
     * Construct and setup the regular expression for this type
     *
     * @param AbstractObject $parent
     * @param string         $definition
     */
    public function __construct(AbstractObject $parent, string $definition)
    {
        $this->pattern = self::PATTERN;

        parent::__construct($parent, $definition);
    }

    /**
     * Parse a type definition string, assuming it belongs to this type
     *
     * @param string $definition
     *
     * @throws Exception
     */
    protected function parseDefinition(string $definition): void
    {
        $definition = self::trim($definition);

        $match = [];
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable IPv6 definition: '{$definition}'");
        }

        if (!in_array(strtolower($match[1]), self::$formats)) {
            throw new Exception("Not an IPv6: '{$definition}'");
        }

        $this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
    }

    /**
     * Check (and optionally reformat) a default value
     *
     * @param string $value
     *
     * @return string
     * @throws Exception
     */
    protected function validateDefault(string $value): string
    {
        if (empty($value)) {
            throw new Exception("Empty IPv6 default");
        }

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw new Exception("Invalid IPv6 default value: '{$value}'");
        }

        return $value;
    }

    public static function getFormats(): array
    {
        return self::$formats;
    }

    public static function setFormats(array $formats): void
    {
        self::$formats = $formats;
    }

}
