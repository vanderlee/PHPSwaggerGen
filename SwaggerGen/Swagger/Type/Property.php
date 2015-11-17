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
		'multi' => 'Array',
		'date' => 'Date',
		'datetime' => 'Date',
		'date-time' => 'Date',
			//'file'	=> 'File';
			//'set'		=> 'EnumArray';
	];

	/**
	 * Description of this property
	 * @var string
	 */
	private $description;

	/**
	 * Type definition of this property
	 * @var Type\AbstractType
	 */
	private $Type;

	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition, $description = null)
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
			throw new Exception('Type format not recognized: ' . $format);
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
