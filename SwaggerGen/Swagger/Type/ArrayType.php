<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic array type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ArrayType extends AbstractType
{

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
	private static $collectionFormats = array(
		'array' => 'csv',
		'csv' => 'csv',
		'ssv' => 'ssv',
		'tsv' => 'tsv',
		'pipes' => 'pipes',
		'multi' => 'multi',
	);

//	//private $allowEmptyValue; // for query/formData
//	private $default;

	/**
	 * @var AbstractType
	 */
	private $Items;
	private $minItems;
	private $maxItems;
	private $collectionFormat;

	protected function parseDefinition($definition)
	{
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Swagger\Exception("Unparseable array definition: '{$definition}'");
		}

		$type = strtolower($match[1]);
		if ($type === 'multi') {
			$parent = $this->getParent();
			if (!($parent instanceof \SwaggerGen\Swagger\Parameter) || !$parent->isMulti()) {
				throw new \SwaggerGen\Swagger\Exception("Multi array only allowed on query or form parameter: '{$definition}'");
			}
		}

		$this->collectionFormat = self::$collectionFormats[$type];

		if (!empty($match[2])) {
			$itemsMatch = array();
			if (preg_match('/^([a-z]+)/i', $match[2], $itemsMatch) !== 1) {
				throw new \SwaggerGen\Swagger\Exception("Unparseable items definition: '{$match[2]}'");
			}
			$itemsFormat = strtolower($itemsMatch[1]);
			if (isset(self::$classTypes[$itemsFormat])) {
				$type = self::$classTypes[$itemsFormat];
				$itemsClass = "SwaggerGen\\Swagger\\Type\\{$type}Type";
				$this->Items = new $itemsClass($this, $match[2]);
			} else {
				$this->Items = new ReferenceObjectType($this, $match[2]);
			}
		}

		if (!empty($match[3])) {
			$exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
			$this->minItems = isset($match[4]) ? $match[4] : null;
			$this->maxItems = isset($match[5]) ? $match[5] : null;
			$exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
			if ($this->minItems && $this->maxItems && $this->minItems > $this->maxItems) {
				self::swap($this->minItems, $this->maxItems);
				self::swap($exclusiveMinimum, $exclusiveMaximum);
			}
			$this->minItems = max(0, $exclusiveMinimum ? $this->minItems + 1 : $this->minItems);
			$this->maxItems = max(0, $exclusiveMaximum ? $this->maxItems - 1 : $this->maxItems);
		}
	}

	public function handleCommand($command, $data = null)
	{
		if ($this->Items) {
			$return = $this->Items->handleCommand($command, $data);
			if ($return) {
				return $return;
			}
		}

		switch (strtolower($command)) {
			case 'min':
				$this->minItems = intval($data);
				return $this;

			case 'max':
				$this->maxItems = intval($data);
				return $this;

			case 'items':
				$match = array();
				if (preg_match('/^([a-z]+)/i', $data, $match) !== 1) {
					throw new \SwaggerGen\Swagger\Exception("Unparseable items definition: '{$data}'");
				}
				foreach (self::$classTypes as $type => $format) {
					if (in_array($match[1], $format)) {
						break;
					}
				}
				$classname = "SwaggerGen\\Swagger\\Type\\{$type}Type";
				$this->Items = new $classname($this, $match[2]);
				return $this->Items;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null([
					'type' => 'array',
					'items' => empty($this->Items) ? null : $this->Items->toArray(),
					'collectionFormat' => $this->collectionFormat == 'csv' ? null : $this->collectionFormat,
					'minItems' => $this->minItems,
					'maxItems' => $this->maxItems,
		]);
	}

}
