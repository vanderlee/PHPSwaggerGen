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

	private static $classTypes = [
		'integer' => 'Integer',
		'int' => 'Integer',
		'int32' => 'Integer',
		'int64' => 'Integer',
		'long' => 'Integer',
		'float' => 'Number',
		'double' => 'Number',
		'string' => 'String',
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
			//'file'		=> 'File',	// @todo Only if parent is_a Response and with the right "produces" mime-type
	];

	/**
	 *
	 * @var type
	 */
	private $description = null;
	private $Type;

	//private $required = false;

	public function __construct(AbstractObject $parent, $definition = 'object', $description = null)
	{
		parent::__construct($parent);

		// Parse regex
		$match = [];
		$count = preg_match('/^([a-z]+)/i', $definition, $match);
		$format = strtolower($match[1]);
		if (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$class = "SwaggerGen\\Swagger\\Type\\{$type}Type";
			$this->Type = new $class($this, $definition);
		} else {
			$this->Type = new Type\ReferenceObjectType($this, $definition);
		}

		$this->description = $description;
	}

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
		return \SwaggerGen\Util::array_filter_null(array_merge([
					'description' => $this->description,
								], $this->Type->toArray(), parent::toArray()));
	}

}
