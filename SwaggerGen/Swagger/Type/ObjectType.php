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
	const REGEX_PROP_START = '/^';
	const REGEX_PROP_NAME = '([^?*:]+)';
	const REGEX_PROP_REQUIRED = '([\?\*])?';
	const REGEX_PROP_ASSIGN = ':';
	const REGEX_PROP_DEFINITION = '(.+)';
	const REGEX_PROP_END = '$/';

	private $minProperties = null;
	private $maxProperties = null;
	private $required = array();

	/**
	 * @var Property[]
	 */
	private $properties = array();

	protected function parseDefinition($definition)
	{
		$definition = self::trim($definition);

		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable object definition: '{$definition}'");
		}

		$this->parseFormat($definition, $match);
		$this->parseProperties($definition, $match);
		$this->parseRange($definition, $match);
	}

	private function parseFormat($definition, $match)
	{
		if (strtolower($match[1]) !== 'object') {
			throw new \SwaggerGen\Exception("Not an object: '{$definition}'");
		}
	}

	private function parseProperties($definition, $match)
	{
		if (!empty($match[2])) {
			do {
				if (($property = self::extract_property($match[2])) !== '') {
					$prop_match = array();	 
					if (preg_match(self::REGEX_PROP_START . self::REGEX_PROP_NAME . self::REGEX_PROP_REQUIRED . self::REGEX_PROP_ASSIGN . self::REGEX_PROP_DEFINITION . self::REGEX_PROP_END, $property, $prop_match) !== 1) {
						throw new \SwaggerGen\Exception("Unparseable property definition: '{$property}'");
					}
                    $this->properties[$prop_match[1]] = new Property($this, $prop_match[3]);
                    if ($prop_match[2] !== '*' && $prop_match[2] !== '?') {
						$this->required[$prop_match[1]] = true;
					}
				}
			} while ($property !== '');
		}
	}

	private function parseRange($definition, $match)
	{
		if (!empty($match[3])) {
			if ($match[4] === '' && $match[5] === '') {
				throw new \SwaggerGen\Exception("Empty object range: '{$definition}'");
			}

			$exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
			$this->minProperties = $match[4] === '' ? null : intval($match[4]);
			$this->maxProperties = $match[5] === '' ? null : intval($match[5]);
			$exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
			if ($this->minProperties && $this->maxProperties && $this->minProperties > $this->maxProperties) {
				self::swap($this->minProperties, $this->maxProperties);
				self::swap($exclusiveMinimum, $exclusiveMaximum);
			}
			$this->minProperties = $this->minProperties === null ? null : max(0, $exclusiveMinimum ? $this->minProperties + 1 : $this->minProperties);
			$this->maxProperties = $this->maxProperties === null ? null : max(0, $exclusiveMaximum ? $this->maxProperties - 1 : $this->maxProperties);
		}
	}

	/**
	 * @param string $command The comment command
	 * @param string $data Any data added after the command
	 * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			// type name description...
			case 'property':
			case 'property?':
            case 'property*':
				$definition = self::wordShift($data);
				if (empty($definition)) {
					throw new \SwaggerGen\Exception("Missing property definition");
				}

				$name = self::wordShift($data);
				if (empty($name)) {
					throw new \SwaggerGen\Exception("Missing property name: '{$definition}'");
				}



                unset($this->required[$name]);
				$readOnly = null;
                $propertySuffix = substr($command, -1);
                if ($propertySuffix === '*') {
                    $readOnly = true;
                }
				else if ($propertySuffix !== '?') {
					$this->required[$name] = true;
				}

                $this->properties[$name] = new Property($this, $definition, $data, $readOnly);

				return $this;

			case 'min':
				$this->minProperties = intval($data);
				if ($this->minProperties < 0) {
					throw new \SwaggerGen\Exception("Minimum less than zero: '{$data}'");
				}
				if ($this->maxProperties !== null && $this->minProperties > $this->maxProperties) {
					throw new \SwaggerGen\Exception("Minimum greater than maximum: '{$data}'");
				}
				$this->minProperties = intval($data);
				return $this;

			case 'max':
				$this->maxProperties = intval($data);
				if ($this->minProperties !== null && $this->minProperties > $this->maxProperties) {
					throw new \SwaggerGen\Exception("Maximum less than minimum: '{$data}'");
				}
				if ($this->maxProperties < 0) {
					throw new \SwaggerGen\Exception("Maximum less than zero: '{$data}'");
				}
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array(
					'type' => 'object',
					'required' => array_keys($this->required),
					'properties' => self::objectsToArray($this->properties),
					'minProperties' => $this->minProperties,
					'maxProperties' => $this->maxProperties,
		));
	}

	public function __toString()
	{
		return __CLASS__;
	}

	/**
	 * Extract a property from a comma-separated list of properties.
	 * 
	 * i.e. `a(x(x,x)),b(x)` returns `a(x(x,x))` and changes `$subject` into `b(x)`.
	 * 
	 * @param string $properties string variable
	 * @return string the extracted string
	 */
	private static function extract_property(&$properties)
	{
		$property = '';
		
		$depth = 0;
		$index = 0;
		while ($index < strlen($properties)) {
			$character = $properties{$index++};			

			if (strpos('([<', $character) !== false) {
				++$depth;
			} elseif (strpos(')]>', $character) !== false) {
				--$depth;
			} elseif ($character === ',' && $depth === 0) {
				break;
			}

			$property .= $character;
		}

		$properties = substr($properties, $index);

		return $property;
	}

}
