<?php

namespace SwaggerGen\Swagger\Type;

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
		'date-time' => \DateTime::RFC3339,
	);

	/**
	 * @var String
	 */
	private $format;

	/**
	 * @var \DateTime
	 */
	private $default = null;

	protected function parseDefinition(string $definition): void
	{
		$match = [];
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable date definition: '{$definition}'");
		}

		$type = strtolower($match[1]);

		if (!isset(self::$formats[$type])) {
			throw new \SwaggerGen\Exception("Not a date: '{$definition}'");
		}
		$this->format = self::$formats[$type];

		$this->default = isset($match[2]) && $match[2] !== '' ? $this->validateDefault($match[2]) : null;
	}

	/**
	 * @param string $command The comment command
	 * @param string $data Any data added after the command
	 * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'default':
				$this->default = $this->validateDefault($data);
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'type' => 'string',
					'format' => $this->format,
					'default' => $this->default ? $this->default->format(self::$datetime_formats[$this->format]) : null,
								), parent::toArray()));
	}

	private function validateDefault($value)
	{
		if (empty($value)) {
			throw new \SwaggerGen\Exception("Empty date default");
		}

		if (($DateTime = date_create($value)) !== false) {
			return $DateTime;
		}

		throw new \SwaggerGen\Exception("Invalid '{$this->format}' default: '{$value}'");
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
