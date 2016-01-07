<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Header object, which may be part of a Response.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Header extends AbstractObject
{

	private $type;
	private $description;

	public function __construct(AbstractObject $parent, $type, $description = '')
	{
		parent::__construct($parent);

		$this->type = strtolower($type);
		if (!in_array($this->type, array('string', 'number', 'integer', 'boolean', 'array'))) {
			throw new \SwaggerGen\Exception('Header type not valid: ' . $this->type);
		}

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
		return self::array_filter_null(array_merge(array(
					'type' => $this->type,
					'description' => $this->description,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->type;
	}

}
