<?php

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
		'date' => 'Date',
		'datetime' => 'Date',
		'date-time' => 'Date',
		'object' => 'Object',
	);

	/**
	 * @var string
	 */
	private $description = null;

	/**
	 * @var string
	 */
	private $title = null;

	/**
	 * @var \SwaggerGen\Swagger\Type\AbstractType
	 */
	private $type;

	public function __construct(AbstractObject $parent, $definition = 'object', $description = null)
	{
		parent::__construct($parent);

		// Check if definition set
		if ($this->getSwagger()->hasDefinition($definition)) {
			$this->type = new Type\ReferenceObjectType($this, $definition);
		} else {
			// Parse regex		
			$match = array();
			preg_match('/^([a-z]+)/i', $definition, $match);
			$format = strtolower($match[1]);

			// Internal type if type known and not overwritten by definition
			if (isset(self::$classTypes[$format])) {
				$type = self::$classTypes[$format];
				$class = "SwaggerGen\\Swagger\\Type\\{$type}Type";
				$this->type = new $class($this, $definition);
			} else {
				$this->type = new Type\ReferenceObjectType($this, $definition);
			}		
		}

		$this->description = $description;
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand($command, $data = null)
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

	public function toArray()
	{
		return self::arrayFilterNull(array_merge($this->type->toArray(), array(
					'title' => empty($this->title) ? null : $this->title,
					'description' => empty($this->description) ? null : $this->description,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
