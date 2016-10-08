<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Basic file type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class FileType extends AbstractType
{

	protected function parseDefinition($definition)
	{
		$type = strtolower($definition);

		if ($type !== 'file') {
			throw new \SwaggerGen\Exception("Not a file: '{$definition}'");
		}

		$parent = $this->getParent();
		if (!($parent instanceof \SwaggerGen\Swagger\Parameter) || !$parent->isForm()) {
			throw new \SwaggerGen\Exception("File type '{$definition}' only allowed on form parameter");
		}

		$consumes = $this->getParentClass('\SwaggerGen\Swagger\Operation')->getConsumes();
		if (empty($consumes)) {
			$consumes = $this->getSwagger()->getConsumes();
		}

		$valid_consumes = ((int) in_array('multipart/form-data', $consumes)) + ((int) in_array('application/x-www-form-urlencoded', $consumes));
		if (empty($consumes) || $valid_consumes !== count($consumes)) {
			throw new \SwaggerGen\Exception("File type '{$definition}' without valid consume");
		}
	}

	public function toArray()
	{
		return self::arrayFilterNull(array(
					'type' => 'file',
		));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
