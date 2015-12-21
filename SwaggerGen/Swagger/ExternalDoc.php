<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger External Documentation object, containing additional,
 * externally hosted documentation for one of the other objects.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ExternalDoc extends AbstractObject
{

	private $url;
	private $description;

	public function __construct(AbstractObject $parent, $url, $description = '')
	{
		parent::__construct($parent);
		$this->url = $url;
		$this->description = $description;
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'description':
			case 'url':
				$this->$command = $data;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array_merge(array(
					'description' => $this->description,
					'url' => $this->url,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->url;
	}

}
