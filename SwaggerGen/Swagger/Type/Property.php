<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Describes a property of an object type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Property extends \SwaggerGen\Swagger\AbstractObject
{

	/**
	 * Description of this property
	 * @var string
	 */
	private $description;

    /**
     * Whether property is read only
     * @var bool
     */
	private $readOnly;

	/**
	 * Type definition of this property
	 * @var \SwaggerGen\Swagger\Type\AbstractType
	 */
	private $Type;

	/**
	 * Create a new property
	 * @param \SwaggerGen\Swagger\AbstractObject $parent
	 * @param string $definition Either a built-in type or a definition name
	 * @param string $description description of the property
     * @param bool $readOnly Whether the property is read only
	 * @throws \SwaggerGen\Exception
	 */
	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition, $description = null, $readOnly = null)
	{
		parent::__construct($parent);
		$this->Type = AbstractType::typeFactory($this, $definition, "Not a property: '%s'");
		$this->description = $description;
		$this->readOnly = $readOnly;
	}

	/**
	 * @param string $command The comment command
	 * @param string $data Any data added after the command
	 * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		// Pass through to Type
		if ($this->Type && $this->Type->handleCommand($command, $data)) {
			return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		// Reference + readonly/description result in allOf construct
		// as it's semantically the same and that's what swagger tools
		// like swagger-ui can understand
		$requiresWrap =
			$this->Type instanceof \SwaggerGen\Swagger\Type\ReferenceObjectType
			&& (!empty($this->description) || !is_null($this->readOnly));

		$valueType = $this->Type->toArray();

		if ($requiresWrap) {
			$valueType = array(
				"allOf" => array($valueType),
			);
		}

		return self::arrayFilterNull(array_merge($valueType, array(
					'description' => empty($this->description) ? null : $this->description,
                    'readOnly' => $this->readOnly
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
