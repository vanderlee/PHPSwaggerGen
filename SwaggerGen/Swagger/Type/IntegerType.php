<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic whole-number type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class IntegerType extends AbstractType
{

	const REGEX_RANGE = '(?:([[<])(-?\d*)?,(-?\d*)?([\\]>]))?';
	const REGEX_DEFAULT = '(?:=(-?\d+))?';

	private static $formats = array(
		'int32' => 'int32',
		'integer' => 'int32',
		'int' => 'int32',
		'int64' => 'int64',
		'long' => 'int64',
	);
	private $format;
	//private $allowEmptyValue; // for query/formData
	private $default;
	private $maximum;
	private $exclusiveMaximum;
	private $minimum;
	private $exclusiveMinimum;
	private $enum = array();
	private $multipleOf;

	protected function parseDefinition($definition)
	{
		$match = array();

		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable integer definition: '{$definition}'");
		}

		if (!isset(self::$formats[strtolower($match[1])])) {
			throw new \SwaggerGen\Exception("Not an integer: '{$definition}'");
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

		$this->default = empty($match[6]) ? null : $this->validateDefault($match[6]);
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'default':
				$this->default = $this->validateDefault($data);
				return $this;

			case 'enum':
				$words = self::words_split($data);
				foreach ($words as &$word) {
					$word = intval($word);
				}
				$this->enum = array_merge($this->enum, $words);
				return $this;

			case 'step':
				if (($step = intval($data)) > 0) {
					$this->multipleOf = $step;
				}
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array(
					'type' => 'integer',
					'format' => $this->format,
					'default' => $this->default ? intval($this->default) : null,
					'minimum' => $this->minimum ? intval($this->minimum) : null,
					'exclusiveMinimum' => $this->exclusiveMinimum ? true : null,
					'maximum' => $this->maximum ? intval($this->maximum) : null,
					'exclusiveMaximum' => $this->exclusiveMaximum ? true : null,
					'enum' => $this->enum,
					'multipleOf' => $this->multipleOf,
		));
	}

	public function __toString()
	{
		return __CLASS__;
	}

	private function validateDefault($value)
	{
		if (preg_match('~^-?\d+$~', $value) !== 1) {
			throw new \SwaggerGen\Exception("Invalid integer default: '{$value}'");
		}

		if ($this->maximum) {
			if (($value > $this->maximum) || ($this->exclusiveMaximum && $value == $this->maximum)) {
				throw new \SwaggerGen\Exception("Default integer beyond maximum: '{$value}'");
			}
		}
		if ($this->minimum) {
			if (($value < $this->minimum) || ($this->exclusiveMinimum && $value == $this->minimum)) {
				throw new \SwaggerGen\Exception("Default integer beyond minimum: '{$value}'");
			}
		}

		return $value;
	}

}
