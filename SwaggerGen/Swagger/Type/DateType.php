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

	const REGEX_DEFAULT = '(?:=(\\d{4}-\\d{2}-\\d{2}(?:T\\d{2}:\\d{2}:\\d{2}(?:\\.\\d+)?(?:Z|[-+]\\d{2}:\\d{2}))?))?';

	private static $formats = array(
		'date' => 'date',
		'date-time' => 'date-time',
		'datetime' => 'date-time',
	);
	private static $validations = array(
		'date' => ['Y-m-d'],
		'date-time' => ['Y-m-d\TH:i:sP', 'Y-m-d\TH:i:s.uP', 'Y-m-d\TH:i:s,uP'],
	);
	private $format;
	//private $allowEmptyValue; // for query/formData
	private $default; // @todo support default

	protected function parseDefinition($definition)
	{
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Swagger\Exception("Unparseable date string definition: '{$definition}'");
		}

		$this->format = self::$formats[strtolower($match[1])];

		if (!empty($match[2])) {
			$this->setDefault($match[2]);
		}
	}

	public function setDefault($date)
	{
		//@todo Fix the date into a valid RFC3339 date

		foreach (self::$validations[$this->format] as $format) {
			if (\DateTime::createFromFormat($format, $date) !== false) {
				$this->default = $date;
				return true;
			}
		}

		throw new \SwaggerGen\Swagger\Exception("Invalid default {$this->format} value: '{$date}'");
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'default':
				$this->setDefault($value);
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return \SwaggerGen\Util::array_filter_null([
					'type' => 'string',
					'format' => $this->format,
					'default' => $this->default,
		]);
	}

}
