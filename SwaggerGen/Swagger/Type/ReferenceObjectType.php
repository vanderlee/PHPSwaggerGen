<?php

namespace SwaggerGen\Swagger\Type;

/**
 * A special type representing a reference to an object definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ReferenceObjectType extends AbstractType
{

	private $reference = null;

	protected function parseDefinition($definition)
	{
		$definition = self::trim($definition);

		$match = array();
		if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
			throw new \SwaggerGen\Exception("Unparseable string definition: '{$definition}'");
		}

		$type = strtolower($match[1]);

		$reference = null;
		if ($type === 'refobject') {
			if (isset($match[2]))
				$reference = $match[2];
		} else {
			$reference = $match[1];
		}

		if (empty($reference)) {
			throw new \SwaggerGen\Exception("Referenced object name missing: '{$definition}'");
		}

		$this->reference = $reference;
	}

	public function toArray()
	{
		return self::arrayFilterNull(array(
					'$ref' => $this->getRoot()->resolveReference($this->reference),
		));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->reference;
	}

}
