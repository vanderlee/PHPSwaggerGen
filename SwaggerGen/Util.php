<?php

namespace SwaggerGen;

/**
 * Non-instantiable class contains some utility functions used by the other
 * classes
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Util
{

	private function __construct()
	{
		// Cannot instantiate
	}

	public static function array_filter_null($array)
	{
		return array_filter($array, function($value) {
			return $value !== null && $value !== [];
		});
	}

	/**
	 * Recursively call toArray() on all objects to return an array
	 * @param Array $array
	 */
	public static function arrayToArray(&$array)
	{
		return array_map(function(Swagger\AbstractObject $item) {
			return $item->toArray();
		}, $array);
	}

	/**
	 * Shifts the first word off a text line and returns it
	 * @param string $data
	 * @return string|bool Either the first word or false if no more words available
	 */
	public static function words_shift(&$data)
	{
		if (preg_match('~^(\S+)\s*(.*)$~', $data, $matches) === 1) {
			$data = $matches[2];
			return $matches[1];
		}
		return false;
	}

	/**
	 * Splits a text line in all it's words
	 * @param string $data
	 * @return string
	 */
	public static function words_split($data)
	{
		return preg_split('~\s+~', $data);
	}

}
