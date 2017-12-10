<?php

namespace SwaggerGen\Swagger\Type\Custom;

/**
 * Custom type type interface
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
interface ICustomType
{

	public static function register($type = null);

	public static function unregister($type = null);
}
