<?php
declare(strict_types=1);

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
		'uuid' => 'StringUuid',
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

		if ($in !== 'path' && $in !== 'form' && $in !== 'query' && $in !== 'header') {
			throw new \SwaggerGen\Exception("Invalid in for parameter: '{$in}'");
		}
		$this->in = $in;

		$definition = self::wordShift($data);
		if (empty($definition)) {
			throw new \SwaggerGen\Exception('No type definition for parameter');
		}

		$name = self::wordShift($data);
		if (empty($name)) {
			throw new \SwaggerGen\Exception('No name for parameter');
		} else {
			$this->name = $name;
		}

		$this->description = $data;
		$this->required = (bool) $required;

		// Parse regex
		$match = [];
		if (preg_match('/^([a-z][a-z0-9]*)/i', $definition, $match) === 1) {
			// recognized format
		} elseif (preg_match('/^(\[)(?:.*?)\]$/i', $definition, $match) === 1) {
			$match[1] = 'array';
		} elseif (preg_match('/^(\{)(?:.*?)\}$/i', $definition, $match) === 1) {
			$match[1] = 'object';
		} else {
			throw new \SwaggerGen\Exception("Unparseable parameter format definition: '{$items}'");
		}					
		
		$format = strtolower($match[1]);
		if ($this->getTypeRegistry()->has($format)) {
			$class = $this->getTypeRegistry()->get($format);
			$this->Type = new $class($this, $definition);
		} elseif (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$class = "\\SwaggerGen\\Swagger\\Type\\{$type}Type";
			$this->Type = new $class($this, $definition);
		} else {
			throw new \SwaggerGen\Exception("Type format not recognized: '{$format}'");
		}
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand(string $command, string $data = null)
	{
		// Pass through to Type
		if ($this->Type && $this->Type->handleCommand($command, $data)) {
			return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray(): array
	{
		return self::arrayFilterNull(array_merge(array(
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

	public function getName()
	{
		return $this->name;
	}

}
