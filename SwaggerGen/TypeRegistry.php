<?php
declare(strict_types=1);

namespace SwaggerGen;

use SwaggerGen\Swagger\Type\Custom\ICustomType;

/**
 * Registry of custom types.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
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
        if (is_subclass_of($classname, ICustomType::class, true)) {
            foreach ($classname::getFormats() as $format) {
                $this->formats[$format] = $classname;
            }
        }
    }

    /**
     * Remove type format by explicitly setting it to null (disables it)
     *
     * @param string $name
     */
    public function remove(string $name): void
    {
        $this->formats[$name] = null;
    }

    /**
     * Is a type format known?
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return !empty($this->formats[$name]);
    }

    /**
     * Get the format class name
     *
     * @param string $name
     *
     * @return null|string
     */
    public function get(string $name): ?string
    {
        return !empty($this->formats[$name])
            ? $this->formats[$name]
            : null;
    }

}
