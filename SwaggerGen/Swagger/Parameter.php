<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

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
     * @return bool
     */
    public function isMulti(): bool
    {
        return in_array($this->in, array('query', 'form'));
    }

    /**
     * Return true if the parameter is of type 'formData'
     * @return bool
     */
    public function isForm(): bool
    {
        return $this->in === 'form';
    }

    /**
     * @throws Exception
     */
    public function __construct(AbstractObject $parent, $in, $data, $required = false)
    {
        parent::__construct($parent);

        if ($in !== 'path' && $in !== 'form' && $in !== 'query' && $in !== 'header') {
            throw new Exception("Invalid in for parameter: '{$in}'");
        }
        $this->in = $in;

        $definition = self::wordShift($data);
        if (empty($definition)) {
            throw new Exception('No type definition for parameter');
        }

        $name = self::wordShift($data);
        if (empty($name)) {
            throw new Exception('No name for parameter');
        }

        $this->name = $name;

        $this->description = $data;
        $this->required = (bool)$required;

        // Parse regex
        $match = array();
        if (preg_match('/^([a-z][a-z0-9]*)/i', $definition, $match) === 1) {
            $format = strtolower($match[1]);
        } elseif (preg_match('/^(\[)(?:.*?)\]$/', $definition, $match) === 1) {
            $format = 'array';
        } elseif (preg_match('/^(\{)(?:.*?)\}$/', $definition, $match) === 1) {
            $format = 'object';
        } else {
            throw new Exception('Unparseable parameter format definition: \'' . $definition . '\'');
        }

        if ($this->getTypeRegistry()->has($format)) {
            $class = $this->getTypeRegistry()->get($format);
            $this->Type = new $class($this, $definition);
        } elseif (isset(self::$classTypes[$format])) {
            $type = self::$classTypes[$format];
            $class = "\\SwaggerGen\\Swagger\\Type\\{$type}Type";
            $this->Type = new $class($this, $definition);
        } else {
            throw new Exception("Type format not recognized: '{$format}'");
        }
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
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
