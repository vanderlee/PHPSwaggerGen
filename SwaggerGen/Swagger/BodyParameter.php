<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

/**
 * Describes a Swagger Parameter object of the "body" variety.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class BodyParameter extends AbstractObject implements IParameter
{

    /**
     * @var string|boolean
     */
    private $name = '';
    private $description;
    private $required = false;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @throws Exception
     */
    public function __construct(AbstractObject $parent, $data, $required = false)
    {
        parent::__construct($parent);

        $type = self::wordShift($data);
        if (empty($type)) {
            throw new Exception('No type definition for body parameter');
        }

        $this->name = self::wordShift($data);
        if (empty($this->name)) {
            throw new Exception('No name for body parameter');
        }

        $this->description = $data;
        $this->required = (bool)$required;

        $this->schema = new Schema($this, $type);
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
        $return = $this->schema->handleCommand($command, $data);
        if ($return) {
            return $return;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'name' => $this->name,
            'in' => 'body',
            'description' => empty($this->description) ? null : $this->description,
            'required' => $this->required ? true : null,
            'schema' => $this->schema->toArray(),
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__ . ' ' . $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

}
