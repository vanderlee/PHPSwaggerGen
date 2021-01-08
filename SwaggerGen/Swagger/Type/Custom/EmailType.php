<?php

namespace SwaggerGen\Swagger\Type\Custom;

/**
 * Email type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class EmailType extends \SwaggerGen\Swagger\Type\StringType implements \SwaggerGen\Swagger\Type\Custom\ICustomType
{

	const PATTERN = '\A[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\z';

	/**
	 * List of formats recognized by this class
	 * @var string[]
	 */
	private static $formats = array('email');

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
	protected function parseDefinition(string $definition): void
	{
		$definition = self::trim($definition);

		$match = [];
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable email definition: '{$definition}'");
		}

		if (!in_array(strtolower($match[1]), self::$formats)) {
			throw new \SwaggerGen\Exception("Not an email: '{$definition}'");
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
	protected function validateDefault(string $value): string
	{
		if (empty($value)) {
			throw new \SwaggerGen\Exception('Empty email default');
		}

		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			throw new \SwaggerGen\Exception("Invalid email default value: '{$value}'");
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
