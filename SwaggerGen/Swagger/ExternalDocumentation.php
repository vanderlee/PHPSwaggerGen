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
class ExternalDocumentation extends AbstractObject
{

	private $url;
	private $description;

	public function __construct(AbstractObject $parent, $url, $description = null)
	{
		parent::__construct($parent);
		$this->url = $url;
		$this->description = $description;
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'url':
			case 'description':
				$this->$command = $data;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'url' => $this->url,
					'description' => empty($this->description) ? null : $this->description,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->url;
	}

}
