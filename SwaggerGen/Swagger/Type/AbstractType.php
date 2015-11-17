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
	const REGEX_FORMAT = '([a-z]+)';
	const REGEX_CONTENT = '(?:\((.*)\))?';
	const REGEX_RANGE = '(?:([[<])(\\d*),(\\d*)([\\]>]))?';
	const REGEX_DEFAULT = '(?:=(.+))?';
	const REGEX_END = '$/i';

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
	 * @param string $command
	 * @param string $data
	 * @return boolean
	 */
	public function handleCommand($command, $data = null)
	{
		return false;
	}

}
