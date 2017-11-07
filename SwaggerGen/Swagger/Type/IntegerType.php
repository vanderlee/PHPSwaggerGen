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
	private $default = null;
	private $maximum = null;
	private $exclusiveMaximum = null;
	private $minimum = null;
	private $exclusiveMinimum = null;
	private $enum = array();
	private $multipleOf = null;

	protected function parseDefinition($definition)
	{
		$definition = self::trim($definition);

		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable integer definition: '{$definition}'");
		}

		$this->parseFormat($definition, $match);
		$this->parseRange($definition, $match);
		$this->parseDefault($definition, $match);
	}

	/**
	 * @param string $definition
	 * @param string[] $match
	 */
	private function parseFormat($definition, $match)
	{
		$type = strtolower($match[1]);
		if (!isset(self::$formats[$type])) {
			throw new \SwaggerGen\Exception("Not an integer: '{$definition}'");
		}
		$this->format = self::$formats[$type];
	}

	/**
	 * @param string $definition
	 * @param string[] $match
	 */
	private function parseRange($definition, $match)
	{
		if (!empty($match[2])) {
			if ($match[3] === '' && $match[4] === '') {
				throw new \SwaggerGen\Exception("Empty integer range: '{$definition}'");
			}

			$this->exclusiveMinimum = isset($match[2]) ? ($match[2] == '<') : null;
			$this->minimum = $match[3] === '' ? null : intval($match[3]);
			$this->maximum = $match[4] === '' ? null : intval($match[4]);
			$this->exclusiveMaximum = isset($match[5]) ? ($match[5] == '>') : null;
			if ($this->minimum && $this->maximum && $this->minimum > $this->maximum) {
				self::swap($this->minimum, $this->maximum);
				self::swap($this->exclusiveMinimum, $this->exclusiveMaximum);
			}
		}
	}

	private function parseDefault($definition, $match)
	{
		$this->default = isset($match[6]) && $match[6] !== '' ? $this->validateDefault($match[6]) : null;
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

			case 'enum':
				$words = self::wordSplit($data);
				foreach ($words as &$word) {
					$word = $this->validateDefault($word);
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
		return self::arrayFilterNull(array_merge(array(
					'type' => 'integer',
					'format' => $this->format,
					'default' => $this->default,
					'minimum' => $this->minimum,
					'exclusiveMinimum' => ($this->exclusiveMinimum && !is_null($this->minimum)) ? true : null,
					'maximum' => $this->maximum,
					'exclusiveMaximum' => ($this->exclusiveMaximum && !is_null($this->maximum)) ? true : null,
					'enum' => $this->enum,
					'multipleOf' => $this->multipleOf,
								), parent::toArray()));
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

		return intval($value);
	}

}
