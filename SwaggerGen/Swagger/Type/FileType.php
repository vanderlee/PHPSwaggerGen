<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\Operation;
use SwaggerGen\Swagger\Parameter;

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

	protected function parseDefinition(string $definition): void
	{
		$type = strtolower($definition);

		if ($type !== 'file') {
			throw new Exception("Not a file: '{$definition}'");
		}

		$parent = $this->getParent();
		if (!($parent instanceof Parameter) || !$parent->isForm()) {
			throw new Exception("File type '{$definition}' only allowed on form parameter");
		}

		$operationClass = $this->getParentClass(Operation::class);
		$consumes = $operationClass->getConsumes();
		if (empty($consumes)) {
			$consumes = $this->getSwagger()->getConsumes();
		}

		$valid_consumes = ((int) in_array('multipart/form-data', $consumes)) + ((int) in_array('application/x-www-form-urlencoded', $consumes));
		if (empty($consumes) || $valid_consumes !== count($consumes)) {
			throw new Exception("File type '{$definition}' without valid consume");
		}
	}

	public function toArray(): array
	{
		return self::arrayFilterNull(array_merge(array(
					'type' => 'file',
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
