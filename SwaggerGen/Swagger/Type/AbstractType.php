<?php

namespace SwaggerGen\Swagger\Type;

/**
 * Abstract foundation of all type definitions.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractType extends \SwaggerGen\Swagger\AbstractObject
{

	const REGEX_START = '/^';
	const REGEX_FORMAT = '([a-z][a-z0-9]*)';
	const REGEX_CONTENT = '(?:\((.*)\))?';
	const REGEX_RANGE = '(?:([[<])(\\d*),(\\d*)([\\]>]))?';
	const REGEX_DEFAULT = '(?:=(.+))?';
	const REGEX_END = '$/i';

	private $example = null;

	protected static function swap(&$a, &$b)
	{
		$tmp = $a;
		$a = $b;
		$b = $tmp;
	}

	/**
	 * @var string $definition
	 */
	public function __construct(\SwaggerGen\Swagger\AbstractObject $parent, $definition)
	{
		parent::__construct($parent);

		$this->parseDefinition($definition);
	}

	abstract protected function parseDefinition($definition);

	/**
	 * Overwrites default AbstractObject parser, since Types should not handle
	 * extensions themselves.
	 * 
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'example':
				if ($data === '') {
					throw new \SwaggerGen\Exception("Missing content for type example");
				}
				$json = preg_replace_callback('/([^{}:]+)/', function($match) {
					json_decode($match[1]);
					return json_last_error() === JSON_ERROR_NONE ? $match[1] : json_encode($match[1]);
				}, trim($data));
				$this->example = json_decode($json, true);
				return $this;
		}

		return false;
	}

	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'example' => $this->example,
								), parent::toArray()));
	}

}
