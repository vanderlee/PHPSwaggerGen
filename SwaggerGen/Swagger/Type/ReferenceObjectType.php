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

	protected function parseDefinition($reference)
	{
		$this->reference = $reference;
	}

	public function toArray()
	{
		return self::array_filter_null([
					'type' => 'object',
					'$ref' => '#/definitions/' . $this->reference,
		]);
	}

}
