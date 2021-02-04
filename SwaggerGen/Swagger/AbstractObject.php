<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;
use SwaggerGen\TypeRegistry;

/**
 * Common foundation for all Swagger objects, handling the overall generator
 * structure, extensions and a few generic services.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractObject
{

	private static $mime_types = array(
		'fileform' => 'multipart/form-data',
		'form' => 'application/x-www-form-urlencoded',
		'json' => 'application/json',
		'text' => 'text/plain',
		'utf8' => 'text/plain; charset=utf-8',
		'yml' => 'application/x-yaml',
		'yaml' => 'application/x-yaml',
		'php' => 'text/x-php',
		'xml' => 'text/xml',
	);

	/**
	 * @var AbstractObject
	 */
	private $parent;

	/**
	 * Map of extensions and their (trimmed) values
	 * @var string[]
	 */
	private $extensions = [];

	public function __construct(AbstractObject $parent = null)
	{
		$this->parent = $parent;
	}

	/**
	 * @return AbstractObject
	 */
	protected function getParent(): AbstractObject
	{
		return $this->parent;
	}

    /**
     * @param string $classname
     *
     * @return $this
     */
	protected function getParentClass(string $classname): AbstractObject
	{
		if (is_a($this, $classname)) {
			return $this;
		}

		return $this->parent->getParentClass($classname);
	}

    /**
     * @return Swagger
     */
	protected function getSwagger(): Swagger
    {
		return $this->parent->getSwagger();
	}

	/**
	 * @return TypeRegistry
	 */
	protected function getTypeRegistry(): TypeRegistry
	{
		return $this->parent->getTypeRegistry();
	}

    /**
     * @param string $command
     * @param string|null $data
     * @return AbstractObject|boolean
     */
    public function handleCommand(string $command, string $data = null)
    {
        if (strtolower(substr($command, 0, 2)) === 'x-') {
            $this->extensions[$command] = empty($data) ? $data : trim($data);
            return $this;
        }

        return false;
    }

    /**
	 * @return string[]
     * @throws Exception
	 */
	public function toArray(): array
	{
		return $this->extensions;
	}

    /**
	 * Translate consumes from shortcuts
	 * @param string[] $mimeTypes
	 * @return string[]
	 */
	protected static function translateMimeTypes(array $mimeTypes): array
	{
		foreach ($mimeTypes as &$mimeType) {
			if (isset(self::$mime_types[strtolower($mimeType)])) {
				$mimeType = self::$mime_types[strtolower($mimeType)];
			}
		}

		return $mimeTypes;
	}

	/**
	 * Trim whitespace from a multibyte string
	 * @param string $string
	 * @return string
	 */
	public static function trim(string $string): string
	{
		return mb_ereg_replace('^\s*([\s\S]*?)\s*$', '\1', $string);
	}

	/**
	 * Filter all items from an array where the value is either null or an
	 * empty array.
	 * @param array $array
	 * @return array
	 */
	public static function arrayFilterNull(array $array): array
	{
		return array_filter($array, function($value) {
			return $value !== null && $value !== [];
		});
	}

	/**
	 * Recursively call toArray() on all objects to return an array
	 * @param array $array
	 * @return array
	 */
	public static function objectsToArray(array $array): array
	{
		return array_map(function(AbstractObject $item) {
			return $item->toArray();
		}, $array);
	}

	/**
	 * Shifts the first word off a text line and returns it
	 * @param mixed $data
	 * @return string|bool Either the first word or false if no more words available
	 */
	public static function wordShift(&$data)
	{
		if (preg_match('~^\s*(\S+)\s*(.*)$~s', $data, $matches) === 1) {
			$data = $matches[2];
			return $matches[1];
		}
		return false;
	}

	/**
	 * Splits a text line in all it's words
	 * @param string $data
	 * @return string[]
	 */
	public static function wordSplit($data): array
	{
		return array_values(preg_grep('~\S~', preg_split('~\s+~', $data)));
	}

}
