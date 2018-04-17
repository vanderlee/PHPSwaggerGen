<?php

namespace SwaggerGen;

/**
 * Main class of SwaggerGen which serves as a facade for the entire library.
 *
 * Copyright (c) 2014-2015 Martijn van der Lee
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class SwaggerGen
{

	const FORMAT_ARRAY = '';
	const FORMAT_JSON = 'json';
	const FORMAT_JSON_PRETTY = 'json+';
	const FORMAT_YAML = 'yaml';

	private $host;
	private $basePath;
	private $dirs = array();
	private $defines = array();

	/**
	 * @var TypeRegistry
	 */
	private $typeRegistry = null;

	/**
	 * Create a new SwaggerGen instance
	 * 
	 * @param string $host
	 * @param string $basePath
	 * @param string[] $dirs
	 * @param TypeRegistry $typeRegistry
	 */
	public function __construct($host = '', $basePath = '', $dirs = array(), $typeRegistry = null)
	{
		$this->host = $host;
		$this->basePath = $basePath;
		$this->dirs = $dirs;
		$this->typeRegistry = $typeRegistry;
	}
	
	/**
	 * Set a new type registry
	 * 
	 * @param TypeRegistry $typeRegistry
	 */
	public function setTypeRegistry($typeRegistry = null)
	{
		$this->typeRegistry = $typeRegistry;
	}

	public function define($name, $value = 1)
	{
		$this->defines[$name] = $value;
	}

	public function undefine($name)
	{
		unset($this->defines[$name]);
	}

	/**
	 * @param string $file
	 * @param string[] $dirs
	 * @return Php\Entity\Statement[]
	 */
	private function parsePhpFile($file, $dirs)
	{
		$Parser = new Parser\Php\Parser();
		return $Parser->parse($file, $dirs, $this->defines);
	}

	/**
	 * @param string $file
	 * @param string[] $dirs
	 * @return Php\Entity\Statement[]
	 */
	private function parseTextFile($file, $dirs)
	{
		$Parser = new Parser\Text\Parser();
		return $Parser->parse($file, $dirs, $this->defines);
	}

	/**
	 * @param string $text
	 * @param string[] $dirs
	 * @return Php\Entity\Statement[]
	 */
	private function parseText($text, $dirs)
	{
		$Parser = new Parser\Text\Parser();
		return $Parser->parseText($text, $dirs, $this->defines);
	}

	/**
	 * Creates Swagger\Swagger object and populates it with statements
	 *
	 * This effectively converts the linear list of statements into parse-tree
	 * like structure, performing some checks (like rejecting unknown
	 * subcommands) during the process. Returned Swagger\Swagger object is
	 * ready for serialization with {@see Swagger\Swagger::toArray}
	 *
	 * @param string $host
	 * @param string $basePath
	 * @param Statement[] $statements
	 * @return Swagger\Swagger
	 * @throws StatementException
	 */
	public function parseStatements($host, $basePath, $statements)
	{
		$swagger = new Swagger\Swagger($host, $basePath, $this->typeRegistry);

		$stack = array($swagger); /* @var Swagger\AbstractObject[] $stack */
		foreach ($statements as $statement) {
			try {
				$top = end($stack);

				do {
					$result = $top->handleCommand($statement->getCommand(), $statement->getData());

					if ($result) {
						if ($result !== $top) {
							// Remove all similar classes from array first!
							$classname = get_class($result);
							$stack = array_filter($stack, function($class) use($classname) {
								return !(is_a($class, $classname));
							});

							$stack[] = $result;
						}
					} else {
						$top = prev($stack);
					}
				} while (!$result && $top);
			} catch (\SwaggerGen\Exception $e) {
				throw new \SwaggerGen\StatementException($e->getMessage(), $e->getCode(), $e, $statement);
			}

			if (!$result && !$top) {
				$messages = array("Unsupported or unknown command: {$statement->getCommand()} {$statement->getData()}");

				$stacktrace = array();
				foreach ($stack as $object) {
					$stacktrace[] = (string) $object;
				}
				$messages[] = join(", \n", $stacktrace);

				throw new \SwaggerGen\StatementException(join('. ', $messages), 0, null, $statement);
			}
		}

		return $swagger;
	}

	/**
	 * Get Swagger 2.x output
	 *
	 * @param string[] $files
	 * @param string[] $dirs
	 * @param string $format
	 * @return type
	 * @throws \SwaggerGen\Exception
	 */
	public function getSwagger($files, $dirs = array(), $format = self::FORMAT_ARRAY)
	{
		$dirs = array_merge($this->dirs, $dirs);

		$statements = array();
		foreach ($files as $file) {
			switch (pathinfo($file, PATHINFO_EXTENSION)) {
				case 'php':
					$fileStatements = $this->parsePhpFile($file, $dirs);
					break;

				case 'txt':
					$fileStatements = $this->parseTextFile($file, $dirs);
					break;

				default:
					$fileStatements = $this->parseText($file, $dirs);
					break;
			}

			$statements = array_merge($statements, $fileStatements);
		}

		$output = $this->parseStatements($this->host, $this->basePath, $statements)->toArray();

		switch ($format) {
			case self::FORMAT_JSON:
				$output = json_encode($output);
				break;

			case self::FORMAT_JSON_PRETTY:
				$flags = (defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0); // Since PHP 5.4.0
				$output = json_encode($output, $flags);
				break;

			case self::FORMAT_YAML:
				if (!function_exists('yaml_emit')) {
					throw new Exception('YAML extension not installed.');
				}
				array_walk_recursive($output, function(&$value) {
					if (is_object($value)) {
						$value = (array) $value;
					}
				});
				$output = yaml_emit($output, YAML_UTF8_ENCODING, YAML_LN_BREAK);
				break;
		}

		return $output;
	}

}
