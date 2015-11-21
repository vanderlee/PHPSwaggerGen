<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic strings type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class StringType extends AbstractType
{

	private static $formats = array(
		'string' => null,
		'byte' => 'byte',
		'binary' => 'binary',
		'password' => 'password',
		'enum' => null,
	);
	private $format;
	//private $allowEmptyValue; // for query/formData
	private $pattern;
	private $default;
	private $maxLength;
	private $minLength;
	private $enum;

	protected function parseDefinition($definition)
	{
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Swagger\Exception("Unparseable string definition: '{$definition}'");
		}

		$type = strtolower($match[1]);

		$this->format = self::$formats[$type];

		if ($type === 'enum') {
			$this->enum = preg_split('/,/', $match[2]);
		} else {
			$this->pattern = empty($match[2]) ? null : $match[2];
		}

		if (!empty($match[3])) {
			if ($match[1] === 'enum') {
				throw new \SwaggerGen\Swagger\Exception("Range not allowed in enumeration definition: '{$definition}'");
			}
			$exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
			$this->minLength = isset($match[4]) ? $match[4] : null;
			$this->maxLength = isset($match[5]) ? $match[5] : null;
			$exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
			if ($this->minLength && $this->maxLength && $this->minLength > $this->maxLength) {
				self::swap($this->minLength, $this->maxLength);
				self::swap($exclusiveMinimum, $exclusiveMaximum);
			}
			$this->minLength = $this->minLength ? max(0, $exclusiveMinimum ? $this->minLength + 1 : $this->minLength) : null;
			$this->maxLength = $this->maxLength ? max(0, $exclusiveMaximum ? $this->maxLength - 1 : $this->maxLength) : null;
		}

		$this->default = empty($match[7]) ? null : $match[7];
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'default':
				$this->default = $data;
				return $this;

			case 'pattern':
				$this->pattern = $data;
				return $this;

			case 'enum':
				$words = self::words_split($data);
				$this->enum = is_array($this->enum) ? array_merge($this->enum, $words) : $words;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null([
					'type' => 'string',
					'format' => $this->format,
					'pattern' => $this->pattern,
					'default' => $this->default,
					'minLength' => $this->minLength,
					'maxLength' => $this->maxLength,
					'enum' => $this->enum,
		]);
	}

}
