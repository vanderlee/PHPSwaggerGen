<?php

namespace SwaggerGen\Swagger\Type\Custom;

/**
 * Custom type interface
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
interface ICustomType
{

    /**
     * Return a list of formats recognized by this type
     *
     * @return string[]
     */
    public static function getFormats(): array;

    /**
     * Overwrite format names recognized by this type
     *
     * @param string[] $formats
     */
    public static function setFormats(array $formats): void;

}
