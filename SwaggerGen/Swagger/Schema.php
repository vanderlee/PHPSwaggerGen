<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\Type\AbstractType;

/**
 * Describes a Swagger Schema object, defining a primitive type, object, array
 * or reference to a defined schema.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Schema extends AbstractDocumentableObject implements IDefinition
{

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $title = null;

    /**
     * @var bool
     */
    private $readOnly = null;

    /**
     * @var AbstractType
     */
    private $type;

    /**
     * @param string $description
     * @throws Exception
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
     * @return AbstractObject|boolean
     * @throws Exception
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
