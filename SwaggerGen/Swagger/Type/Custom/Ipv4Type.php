<?php

namespace SwaggerGen\Swagger\Type\Custom;

/**
 * IPv4 type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Ipv4Type extends \SwaggerGen\Swagger\Type\StringType implements ICustomType
{

	const PATTERN = '^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\\.|$)){4}$';
	const TYPE = 'ipv4';

	public static function register($type = null)
	{
		self::registerType($type === null ? self::TYPE : $type, __CLASS__);
	}

	public static function unregister($type = null)
	{
		self::unregisterType($type === null ? self::TYPE : $type);
	}

	/**
	 * Construct and setup the regular expression for this type
	 * 
	 * @param \SwaggerGen\Swagger\AbstractObject $parent
	 * @param string $definition
	 */
	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition)
	{
		$this->pattern = self::PATTERN;

		parent::__construct($parent, $definition);
	}

	/**
	 * Parse a type definition string, assuming it belongs to this type
	 * 
	 * @param string $definition
	 * @throws \SwaggerGen\Exception
	 */
	protected function parseDefinition($definition)
	{
		$definition = self::trim($definition);

		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable IPv4 definition: '{$definition}'");
		}

		if (strtolower($match[1] !== self::TYPE)) {
			throw new \SwaggerGen\Exception("Not an IPv4: '{$definition}'");
		}

		$this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
	}

	/**
	 * Check (and optionally reformat) a default value
	 * 
	 * @param string $value
	 * @return string
	 * @throws \SwaggerGen\Exception
	 */
	protected function validateDefault($value)
	{
		if (empty($value)) {
			throw new \SwaggerGen\Exception("Empty IPv4 default");
		}

		if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
			throw new \SwaggerGen\Exception("Invalid IPv4 default value: '{$value}'");
		}

		return $value;
	}

}
