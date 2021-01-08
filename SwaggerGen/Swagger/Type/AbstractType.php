<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;
use SwaggerGen\Swagger\AbstractObject;

/**
 * Abstract foundation of all type definitions.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractType extends AbstractObject
{

	const REGEX_START = '/^';
	const REGEX_FORMAT = '([a-z][a-z0-9]*)';
	const REGEX_CONTENT = '(?:\((.*)\))?';
	const REGEX_RANGE = '(?:([[<])(\\d*),(\\d*)([\\]>]))?';
	const REGEX_DEFAULT = '(?:=(.+))?';
	const REGEX_END = '$/i';

	private static $classTypes = array(
		'integer' => 'Integer',
		'int' => 'Integer',
		'int32' => 'Integer',
		'int64' => 'Integer',
		'long' => 'Integer',
		'float' => 'Number',
		'double' => 'Number',
		'string' => 'String',
		'uuid' => 'StringUuid',
		'byte' => 'String',
		'binary' => 'String',
		'password' => 'String',
		'enum' => 'String',
		'boolean' => 'Boolean',
		'bool' => 'Boolean',
		'array' => 'Array',
		'csv' => 'Array',
		'ssv' => 'Array',
		'tsv' => 'Array',
		'pipes' => 'Array',
		'date' => 'Date',
		'datetime' => 'Date',
		'date-time' => 'Date',
		'object' => 'Object',
		'refobject' => 'ReferenceObject',
		'allof' => 'AllOf',
	);

	private $example = null;

	/**
	 * Swap values of two variables.
	 * Used for sorting.
	 * @param mixed $a
	 * @param mixed $b
	 */
	protected static function swap(&$a, &$b)
	{
		$tmp = $a;
		$a = $b;
		$b = $tmp;
	}

	/**
	 * @param string $list
	 * @return array
	 */
	protected static function parseList($list)
	{
		$ret = [];
		while ($item = self::parseListItem($list)) {
			$ret[] = $item;
		}
		return $ret;
	}

	/**
	 * Extract an item from a comma-separated list of items.
	 *
	 * i.e. `a(x(x,x)),b(x)` returns `a(x(x,x))` and changes `$list` into `b(x)`.
	 * Note: brace nesting is not checked, e.g. `a{b(})` is a valid list item.
	 *
	 * @param string $list the list to parse
	 * @return string the extracted item
	 */
	protected static function parseListItem(&$list)
	{
		$item = '';

		$depth = 0;
		$index = 0;
		while ($index < strlen($list)) {
			$c = $list[$index++];

			if (strpos('{([<', $c) !== false) {
				++$depth;
			} elseif (strpos('})]>', $c) !== false) {
				--$depth;
			} elseif ($c === ',' && $depth === 0) {
				break;
			}

			$item .= $c;
		}
		$list = substr($list, $index);

		return $item;
	}

	/**
	 * @var string $definition
	 */
	public function __construct(AbstractObject $parent, $definition)
	{
		parent::__construct($parent);

		$this->parseDefinition($definition);
	}

    /**
     * @param string $definition
     *
     * @throws Exception
     */
	abstract protected function parseDefinition(string $definition): void;

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
					throw new Exception("Missing content for type example");
				}
				$json = preg_replace_callback('/([^{}:,]+)/', function($match) {
					json_decode($match[1]);
					return json_last_error() === JSON_ERROR_NONE ? $match[1] : json_encode($match[1]);
				}, trim($data));
				$this->example = json_decode($json, true);

				// In case input contains special chars, the above preg_replace would fail
				//   Input could be a well-formed json already though
				if ($this->example === null) {
					$this->example = json_decode($data, true);
				}
				// If all fails, use input as-is
				if ($this->example === null) {
					$this->example = $data;
				}

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

    /**
     * @param AbstractObject $parent
     * @param string         $definition
     * @param string         $error
     *
     * @return self
     * @throws Exception
     */
	public static function typeFactory($parent, $definition, $error = "Unparseable schema type definition: '%s'")
	{
		// Parse regex
		$match = [];
		if (preg_match('/^([a-z]+)/i', $definition, $match) === 1) {
			// recognized format
		} elseif (preg_match('/^(\[)(?:.*?)\]$/i', $definition, $match) === 1) {
			$match[1] = 'array';
		} elseif (preg_match('/^(\{)(?:.*?)\}$/i', $definition, $match) === 1) {
			$match[1] = 'object';
		} else {
			throw new Exception(sprintf($error, $definition));
		}
		$format = strtolower($match[1]);
		// Internal type if type known and not overwritten by definition
		if ($parent->getTypeRegistry()->has($format)) {
			$class = $parent->getTypeRegistry()->get($format);
			return new $class($parent, $definition);
		} elseif (isset(self::$classTypes[$format])) {
			$type = self::$classTypes[$format];
			$class = "\\SwaggerGen\\Swagger\\Type\\{$type}Type";
			return new $class($parent, $definition);
		} else {
			return new ReferenceObjectType($parent, $definition);
		}
	}

}
