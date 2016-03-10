<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Parameter object of all varieties except "body".
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parameter extends AbstractObject implements IParameter
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
		'file' => 'File',
			//'set'		=> 'EnumArray',
	);
	private $name = '';
	private $in;
	private $description;
	private $required = false;

	/**
	 * @var Type\AbstractType
	 */
	private $Type;

	/**
	 * Returns true if the "multi" array collectionFormat is allowed for this
	 * parameter.
	 * @return boolean
	 */
	public function isMulti()
	{
		return in_array($this->in, array('query', 'form'));
	}

	/**
	 * Return true if the parameter is of type 'formData'
	 * @return type
	 */
	public function isForm()
	{
		return $this->in === 'form';
	}

	public function __construct(AbstractObject $parent, $in, $data, $required = false)
	{
		parent::__construct($parent);

		if ($in !== 'path' && $in !== 'form' && $in !== 'query' && $in !== 'head') {
			throw new \SwaggerGen\Exception("Invalid in for parameter: '{$in}'");
		}
		$this->in = $in;

		$definition = self::words_shift($data);
		if (empty($definition)) {
			throw new \SwaggerGen\Exception('No type definition for parameter');
		}

		$this->name = self::words_shift($data);
		if (empty($this->name)) {
			throw new \SwaggerGen\Exception('No name for parameter');
		}

		$this->description = $data;
		$this->required = (bool) $required;

		// Parse regex
		$match = array();
		$count = preg_match('/^([a-z]+)/i', $definition, $match);
		$format = strtolower($match[1]);
		if (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$class = "SwaggerGen\\Swagger\\Type\\{$type}Type";
			$this->Type = new $class($this, $definition);
		} else {
			throw new \SwaggerGen\Exception("Type format not recognized: '{$format}'");
		}
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
		return self::array_filter_null(array_merge(array(
					'name' => $this->name,
					'in' => $this->in === 'form' ? 'formData' : $this->in,
					'description' => empty($this->description) ? null : $this->description,
					'required' => $this->in === 'path' || $this->required ? true : null,
								), $this->Type->toArray(), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . " {$this->name} {$this->in}";
	}

}
