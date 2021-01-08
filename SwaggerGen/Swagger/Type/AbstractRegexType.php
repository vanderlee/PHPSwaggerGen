<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Abstract regular expression string definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AbstractRegexType extends StringType
{

	const REGEX_DEFAULT_START = '(?:=(';
	const REGEX_DEFAULT_END = '))?';

	/**
	 * The raw regular expression to use.
	 * Exclude start (`^`) and end (`$`) anchors.
	 * @var string
	 */
	private $regex = null;
	
	/**
	 * Construct and setup the regular expression for this type
	 * 
	 * @param \SwaggerGen\Swagger\AbstractObject $parent
	 * @param string $definition
	 * @param string $format Name of the string format
	 * @param string $regex Regular expression to use as the format and for default validation
	 */
	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition, $format, $regex)
	{
		$this->format = $format;
		$this->regex = $regex;

		parent::__construct($parent, $definition);
	}

	protected function parseDefinition(string $definition): void
	{
		$definition = self::trim($definition);

		$match = [];
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT_START . $this->regex . self::REGEX_DEFAULT_END . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable {$this->format} definition: '{$definition}'");
		}

		if (strtolower($match[1] !== $this->format)) {
			throw new \SwaggerGen\Exception("Not a {$this->format}: '{$definition}'");
		}

		$this->pattern = '^' . $this->regex . '$';
		$this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
	}

	protected function validateDefault(string $value): string
	{
		$value = parent::validateDefault($value);
		
		if (preg_match('/' . $this->pattern . '/', $value) !== 1) {
			throw new \SwaggerGen\Exception("Invalid {$this->format} default value");
		}
		
		return $value;
	}

}
