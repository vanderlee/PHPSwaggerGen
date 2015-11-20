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

		$parent = $this->getParent();
		if (!($parent instanceof \SwaggerGen\Swagger\Parameter) || !$parent->isForm()) {
			throw new \SwaggerGen\Swagger\Exception("File type only allowed on form parameter: '{$definition}'");
		}

		$consumes = $this->getParentClass('\SwaggerGen\Swagger\Operation')->getConsumes();
		if (empty($consumes)) {
			$consumes = $this->getRoot()->getConsumes();
		}

		if ($consumes !== array('multipart/form-data') && $consumes !== array('application/x-www-form-urlencoded')) {
			throw new \SwaggerGen\Swagger\Exception("File type without valid consume: '{$definition}'");
		}
	}

	public function toArray()
	{
		return \SwaggerGen\Util::array_filter_null([
					'type' => 'file',
		]);
	}

}
