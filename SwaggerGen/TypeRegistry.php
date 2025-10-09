<?php

namespace SwaggerGen;

use SwaggerGen\Swagger\Type\Custom\ICustomType;

/**
 * Registry of custom types.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class TypeRegistry
{

    /**
     * Map of format-name => class-name
     *
     * @var array
     */
    private $formats = [];

    /**
     * Add a type name from classname
     *
     * @param string $classname
     */
    public function add(string $classname): void
    {
        if (is_subclass_of($classname, ICustomType::class)) {
            foreach ($classname::getFormats() as $format) {
                $this->formats[$format] = $classname;
            }
        }
    }

    /**
     * Remove type format by explicitly nulling it (disables it)
     *
     * @param string $name
     */
    public function remove($name): void
    {
        $this->formats[$name] = null;
    }

    /**
     * Is a type format known?
     *
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        return !empty($this->formats[$name]);
    }

    /**
     * Get the format class name
     *
     * @param $name
     * @return null|string
     */
    public function get($name): ?string
    {
        return !empty($this->formats[$name])
            ? $this->formats[$name]
            : null;
    }

}
