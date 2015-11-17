<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Tag object, used to categorize operations.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Tag extends AbstractDocumentableObject
{

	private $name;
	private $description;

	public function __construct(AbstractObject $parent, $name, $description = '')
	{
		parent::__construct($parent);
		$this->name = $name;
		$this->description = $description;
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'description':
				$this->description = $data;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return \SwaggerGen\Util::array_filter_null(array_merge([
					'name' => $this->name,
					'description' => $this->description,
								], parent::toArray()));
	}

	public function getName()
	{
		return $this->name;
	}

}
