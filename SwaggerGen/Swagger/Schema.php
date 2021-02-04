<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Schema object, defining a primitive type, object, array
 * or reference to a defined schema.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Schema extends AbstractDocumentableObject implements IDefinition
{

	/**
	 * @var string
	 */
	private $description = null;

	/**
	 * @var string
	 */
	private $title = null;

	/**
	 * @var bool
	 */
	private $readOnly = null;

	/**
	 * @var \SwaggerGen\Swagger\Type\AbstractType
	 */
	private $type;

	/**
	 * @param string $description
	 */
	public function __construct(AbstractObject $parent, $definition = 'object', $description = null)
	{
		parent::__construct($parent);

		// Check if definition set
		if ($this->getSwagger()->hasDefinition($definition)) {
			$this->type = new Type\ReferenceObjectType($this, $definition);
		} else {
			$this->type = Type\AbstractType::typeFactory($this, $definition);
		}

		$this->description = $description;
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand(string $command, string $data = null)
	{
		// Pass through to Type
		if ($this->type && $this->type->handleCommand($command, $data)) {
			return $this;
		}

		// handle all the rest manually
		switch (strtolower($command)) {
			case 'description':
				$this->description = $data;
				return $this;
				
			case 'title':
				$this->title = $data;
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray(): array
	{
		return self::arrayFilterNull(array_merge($this->type->toArray(), array(
					'title' => empty($this->title) ? null : $this->title,
					'description' => empty($this->description) ? null : $this->description,
					'readOnly' => $this->readOnly
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

	public function setReadOnly()
	{
		$this->readOnly = true;
	}

}
