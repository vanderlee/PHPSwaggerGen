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

	const REGEX_ARRAY_CONTENT = '(?:(\[)(.*)\])?';

	private static $classTypes = array(
		'integer' => 'Integer',
		'int' => 'Integer',
		'int32' => 'Integer',
		'int64' => 'Integer',
		'long' => 'Integer',
		'float' => 'Number',
		'double' => 'Number',
		'string' => 'String',
		'uuid' => 'StringUuid',
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
		'object' => 'Object',
	);
	private static $collectionFormats = array(
		'array' => 'csv',
		'csv' => 'csv',
		'ssv' => 'ssv',
		'tsv' => 'tsv',
		'pipes' => 'pipes',
		'multi' => 'multi',
	);

	/**
	 * @var AbstractType
	 */
	private $Items = null;
	private $minItems = null;
	private $maxItems = null;
	private $collectionFormat = null;

	protected function parseDefinition($definition)
	{
		$definition = self::trim($definition);
		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) === 1) {
			// recognized format
		} elseif (preg_match(self::REGEX_START . self::REGEX_ARRAY_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) === 1) {
			$match[1] = 'array';
		} else {
			throw new \SwaggerGen\Exception("Unparseable array definition: '{$definition}'");
		}

		$this->parseFormat($definition, $match);
		$this->parseItems($definition, $match);
		$this->parseRange($definition, $match);
	}

	private function parseFormat($definition, $match)
	{
		$type = strtolower($match[1]);
		if (!isset(self::$collectionFormats[$type])) {
			throw new \SwaggerGen\Exception("Not an array: '{$definition}'");
		}

		if ($type === 'multi') {
			$parent = $this->getParent();
			if (!($parent instanceof \SwaggerGen\Swagger\Parameter) || !$parent->isMulti()) {
				throw new \SwaggerGen\Exception("Multi array only allowed on query or form parameter: '{$definition}'");
			}
		}

		$this->collectionFormat = self::$collectionFormats[$type];
	}

	private function parseItems($definition, $match)
	{
		if (!empty($match[2])) {
			$this->Items = $this->validateItems($match[2]);
		}
	}

	private function parseRange($definition, $match)
	{
		if (!empty($match[3])) {
			if ($match[4] === '' && $match[5] === '') {
				throw new \SwaggerGen\Exception("Empty array range: '{$definition}'");
			}

			$exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
			$this->minItems = $match[4] === '' ? null : intval($match[4]);
			$this->maxItems = $match[5] === '' ? null : intval($match[5]);
			$exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
			if ($this->minItems && $this->maxItems && $this->minItems > $this->maxItems) {
				self::swap($this->minItems, $this->maxItems);
				self::swap($exclusiveMinimum, $exclusiveMaximum);
			}
			$this->minItems = $this->minItems === null ? null : max(0, $exclusiveMinimum ? $this->minItems + 1 : $this->minItems);
			$this->maxItems = $this->maxItems === null ? null : max(0, $exclusiveMaximum ? $this->maxItems - 1 : $this->maxItems);
		}
	}

	/**
	 * @param string $command The comment command
	 * @param string $data Any data added after the command
	 * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
	 */
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
				if ($this->minItems < 0) {
					throw new \SwaggerGen\Exception("Minimum less than zero: '{$data}'");
				}
				if ($this->maxItems !== null && $this->minItems > $this->maxItems) {
					throw new \SwaggerGen\Exception("Minimum greater than maximum: '{$data}'");
				}
				return $this;

			case 'max':
				$this->maxItems = intval($data);
				if ($this->minItems !== null && $this->minItems > $this->maxItems) {
					throw new \SwaggerGen\Exception("Maximum less than minimum: '{$data}'");
				}
				if ($this->maxItems < 0) {
					throw new \SwaggerGen\Exception("Maximum less than zero: '{$data}'");
				}
				return $this;

			case 'items':
				$this->Items = $this->validateItems($data);
				return $this->Items;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array(
					'type' => 'array',
					'items' => empty($this->Items) ? null : $this->Items->toArray(),
					'collectionFormat' => $this->collectionFormat == 'csv' ? null : $this->collectionFormat,
					'minItems' => $this->minItems,
					'maxItems' => $this->maxItems,
		));
	}

	private function validateItems($items)
	{
		if (empty($items)) {
			throw new \SwaggerGen\Exception("Empty items definition: '{$items}'");
		}

		$match = array();
		if (preg_match('/^([a-z]+)/i', $items, $match) === 1) {
			// recognized format
		} elseif (preg_match('/^(\[)(?:.*?)\]$/i', $items, $match) === 1) {
			$match[1] = 'array';
		} elseif (preg_match('/^(\{)(?:.*?)\}$/i', $items, $match) === 1) {
			$match[1] = 'object';
		} else {
			throw new \SwaggerGen\Exception("Unparseable items definition: '{$items}'");
		}
		$format = strtolower($match[1]);
		if (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$typeClass = "SwaggerGen\\Swagger\\Type\\{$type}Type";
			return new $typeClass($this, $items);
		}

		return new ReferenceObjectType($this, $items);
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
