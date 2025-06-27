<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

/**
 * Describes a Swagger Header object, which may be part of a Response.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Header extends AbstractObject
{

    private $type;
    private $description;

    /**
     * @throws Exception
     */
    public function __construct(AbstractObject $parent, $type, $description = null)
    {
        parent::__construct($parent);

        $this->type = strtolower($type);
        if (!in_array($this->type, array('string', 'number', 'integer', 'boolean', 'array'))) {
            throw new Exception('Header type not valid: \'' . $type . '\'');
        }

        $this->description = $description;
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     */
    public function handleCommand($command, $data = null)
    {
        if (strtolower($command) === 'description') {
            $this->description = $data;
            return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'type' => $this->type,
            'description' => empty($this->description) ? null : $this->description,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__ . ' ' . $this->type;
    }

}
