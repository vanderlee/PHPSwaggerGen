<?php

namespace CustomType;

/**
 * IPv4 type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Ipv4Type extends \SwaggerGen\Swagger\Type\StringType
{
	
	const PATTERN = '^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\\.|$)){4}$';
	const TYPE = 'ipv4';

	public static function register()
	{
		self::registerType(self::TYPE, __CLASS__);
	}
	
	/**
	 * Construct and setup the regular expression for this type
	 * 
	 * @param \SwaggerGen\Swagger\AbstractObject $parent
	 * @param string $definition
	 */
	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition)
	{
		$this->format = 'string';
		$this->pattern = self::PATTERN;
		
		parent::__construct($parent, $definition);
	}
	
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
	
	protected function validateDefault($value)
	{
		$value = parent::validateDefault($value);
		
		if (preg_match('/' . $this->pattern . '/', $value) !== 1) {
			throw new \SwaggerGen\Exception("Invalid {$this->format} default value");
		}
		
		return $value;
	}

}

\CustomType\Ipv4Type::register();
