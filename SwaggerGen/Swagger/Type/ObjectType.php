<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic object type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ObjectType extends AbstractType
{

	const REGEX_PROP_START = '/([^:\?]+)(\?)?:';
	const REGEX_PROP_FORMAT = '[a-z]+';
	const REGEX_PROP_CONTENT = '(?:\(.*\))?';
	const REGEX_PROP_RANGE = '(?:[[<][^,]*,[^,]*[\\]>])?';
	const REGEX_PROP_DEFAULT = '(?:=.+?)?';
	const REGEX_PROP_END = '(?:,|$)/i';

	private static $classTypes = array(
		'integer' => 'Integer',
		'int' => 'Integer',
		'int32' => 'Integer',
		'int64' => 'Integer',
		'long' => 'Integer',
		'float' => 'Number',
		'double' => 'Number',
		'string' => 'String',
		'byte' => 'String',
		'binary' => 'String',
		'password' => 'String',
		'enum' => 'String',
		'boolean' => 'Boolean',
		'bool' => 'Boolean',
		'array' => 'Array',
		'csv' => 'Array',
		'ssv' => 'Array',
		'tsv' => 'Array',
		'pipes' => 'Array',
		'date' => 'Date',
		'datetime' => 'Date',
		'date-time' => 'Date',
			//'set'		=> 'EnumArray';
	);

	/**
	 * @var AbstractType
	 */
	private $Properties = array();
	private $minProperties = null;
	private $maxProperties = null;
	private $required = array();
	private $properties = array();

	protected function parseDefinition($definition)
	{
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Swagger\Exception("Unparseable object definition: '{$definition}'");
		}

		$type = strtolower($match[1]);

		if (!empty($match[2])) {
			$prop_matches = array();
			preg_match_all(self::REGEX_PROP_START . '(' . self::REGEX_PROP_FORMAT . self::REGEX_PROP_CONTENT . self::REGEX_PROP_RANGE . self::REGEX_PROP_DEFAULT . ')' . self::REGEX_PROP_END, $match[2], $prop_matches, PREG_SET_ORDER);
			foreach ($prop_matches as $prop_match) {
				$this->properties[$prop_match[1]] = new Property($this, $prop_match[3]);
				if ($prop_match[2] !== '?') {
					$this->required[] = $prop_match[1];
				}
			}
		}

		if (!empty($match[3])) {
			$exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
			$this->minProperties = isset($match[4]) ? $match[4] : null;
			$this->maxProperties = isset($match[5]) ? $match[5] : null;
			$exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
			if ($this->minProperties && $this->maxProperties && $this->minProperties > $this->maxProperties) {
				self::swap($this->minProperties, $this->maxProperties);
				self::swap($exclusiveMinimum, $exclusiveMaximum);
			}
			$this->minProperties = max(0, $exclusiveMinimum ? $this->minProperties + 1 : $this->minProperties);
			$this->maxProperties = max(0, $exclusiveMaximum ? $this->maxProperties - 1 : $this->maxProperties);
		}
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			// type name description...
			case 'property':
			case 'property?':
				$definition = \SwaggerGen\Util::words_shift($data);
				$name = \SwaggerGen\Util::words_shift($data);
				$this->properties[$name] = new Property($this, $definition, $data);

				if (substr($command, -1) !== '?') {
					$this->required[] = $name;
				}
				return $this;

			case 'min':
				$this->minProperties = intval($data);
				return $this;

			case 'max':
				$this->maxProperties = intval($data);
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return \SwaggerGen\Util::array_filter_null([
					'type' => 'object',
					'required' => $this->required,
					'properties' => \SwaggerGen\Util::arrayToArray($this->properties),
					'minProperties' => $this->minProperties,
					'maxProperties' => $this->maxProperties,
		]);
	}

}
