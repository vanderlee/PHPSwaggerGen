<?php
declare(strict_types=1);

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

	public function __construct(AbstractObject $parent, $name, $description = null)
	{
		parent::__construct($parent);
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand(string $command, string $data = null)
	{
		switch (strtolower($command)) {
			case 'description':
				$this->description = $data;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray(): array
	{
		return self::arrayFilterNull(array_merge(array(
					'name' => $this->name,
					'description' => empty($this->description) ? null : $this->description,
								), parent::toArray()));
	}

	public function getName()
	{
		return $this->name;
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->name;
	}

}
