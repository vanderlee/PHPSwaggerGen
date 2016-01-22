<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic decimal-point numeric type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class NumberType extends AbstractType
{

	const REGEX_RANGE = '(?:([[<])(-?(?=.?\d)\d*\.?\d*)?,(-?(?=.?\d)\d*\.?\d*)?([\\]>]))?';
	const REGEX_DEFAULT = '(?:=(-?(?=.?\d)\d*\.?\d*))?';

	private static $formats = array(
		'float' => 'float',
		'double' => 'double',
	);
	private $format;
	//private $allowEmptyValue; // for query/formData
	private $default;
	private $maximum;
	private $exclusiveMaximum;
	private $minimum;
	private $exclusiveMinimum;
	private $enum;
	private $multipleOf;

	protected function parseDefinition($definition)
	{
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable number definition: '{$definition}'");
		}

		$this->format = self::$formats[strtolower($match[1])];

		if (!empty($match[2])) {
			$this->exclusiveMinimum = isset($match[2]) ? ($match[2] == '<') : null;
			$this->minimum = isset($match[3]) ? $match[3] : null;
			$this->maximum = isset($match[4]) ? $match[4] : null;
			$this->exclusiveMaximum = isset($match[5]) ? ($match[5] == '>') : null;
			if ($this->minimum && $this->maximum && $this->minimum > $this->maximum) {
				self::swap($this->minimum, $this->maximum);
				self::swap($this->exclusiveMinimum, $this->exclusiveMaximum);
			}
		}

		$this->default = empty($match[6]) ? null : doubleval($match[6]);
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'default':
				$this->default = doubleval($data);
				return $this;

			case 'enum':
				$words = self::words_split($data);
				array_walk($words, 'doubleval');
				$this->enum = array_merge($this->enum, $words);
				return $this;

			case 'step':
				if (($step = doubleval($data)) > 0) {
					$this->multipleOf = $step;
				}
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array(
					'type' => 'number',
					'format' => $this->format,
					'default' => $this->default,
					'minimum' => $this->minimum,
					'exclusiveMinimum' => $this->exclusiveMinimum,
					'maximum' => $this->maximum,
					'exclusiveMaximum' => $this->exclusiveMaximum,
					'enum' => $this->enum,
					'multipleOf' => $this->multipleOf,
		));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
