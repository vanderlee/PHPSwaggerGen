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

	private static $classTypes = array(
		'integer' => 'Integer',
		'int' => 'Integer',
		'int32' => 'Integer',
		'int64' => 'Integer',
		'long' => 'Integer',
		'float' => 'Number',
		'double' => 'Number',
		'string' => 'String',
		'uuid' => 'StringUuid',
		'byte' => 'String',
		'binary' => 'String',
		'password' => 'String',
		'enum' => 'String',
		'boolean' => 'Boolean',
		'bool' => 'Boolean',
		'array' => 'Array',
		'csv' => 'Array',
		'ssv' => 'Array',
		'tsv' => 'Array',
		'pipes' => 'Array',
		'multi' => 'Array',
		'date' => 'Date',
		'datetime' => 'Date',
		'date-time' => 'Date',
		'object' => 'Object',
		'refobject' => 'ReferenceObject',
	);

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

		// Parse regex
		$match = array();
		if (preg_match('/^([a-z]+)/i', $definition, $match) === 1) {
			// recognized format
		} elseif (preg_match('/^(\[)(?:.*?)\]$/i', $definition, $match) === 1) {
			$match[1] = 'array';
		} elseif (preg_match('/^(\{)(?:.*?)\}$/i', $definition, $match) === 1) {
			$match[1] = 'object';
		} else {
			throw new \SwaggerGen\Exception("Not a property: '{$definition}'");
		}
		$format = strtolower($match[1]);
		if (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$class = "SwaggerGen\\Swagger\\Type\\{$type}Type";
			$this->Type = new $class($this, $definition);
		} else {
			$this->Type = new \SwaggerGen\Swagger\Type\ReferenceObjectType($this, $definition);
		}

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
		return self::arrayFilterNull(array_merge($this->Type->toArray(), array(
					'description' => empty($this->description) ? null : $this->description,
                    'readOnly' => $this->readOnly
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
