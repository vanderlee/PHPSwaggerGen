<?php

namespace SwaggerGen\Swagger;

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

	private static $mime_types = [
		'fileform' => 'multipart/form-data',
		'form' => 'application/x-www-form-urlencoded',
		'json' => 'application/json',
		'text' => 'text/plain',
		'utf8' => 'text/plain; charset=utf-8',
		'yml' => 'application/x-yaml',
		'yaml' => 'application/x-yaml',
		'php' => 'text/x-php',
		'xml' => 'text/xml',
	];

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
	protected function getParent()
	{
		return $this->parent;
	}

	protected function getParentClass($classname)
	{
		if (is_a($this, $classname)) {
			return $this;
		}
		return $this->parent->getParentClass($classname);
	}

	/**
	 * @return SwaggerGen\Swagger
	 */
	protected function getRoot()
	{
		return $this->parent->getRoot();
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return AbstractObject
	 */
	public function handleCommand($command, $data = null)
	{
		if (strtolower(substr($command, 0, 2)) === 'x-') {
			$this->extensions[$command] = empty($data) ? $data : trim($data);
			return $this;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->extensions;
	}

	/**
	 * Translate consumes from shortcuts
	 * @param String[] $mimeTypes
	 */
	protected static function translateMimeTypes(Array $mimeTypes)
	{
		foreach ($mimeTypes as &$mimeType) {
			if (isset(self::$mime_types[strtolower($mimeType)])) {
				$mimeType = self::$mime_types[strtolower($mimeType)];
			}
		}

		return $mimeTypes;
	}

}
