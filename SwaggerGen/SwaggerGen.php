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

	private $host;
	private $basePath;
	private $dirs = [];
	private $defines = [];

	public function __construct($host = '', $basePath = '', $dirs = [])
	{
		$this->host = $host;
		$this->basePath = $basePath;
		$this->dirs = $dirs;
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
		return $Parser->parse($file, $dirs);
	}

	/**
	 * @param string $file
	 * @param string[] $dirs
	 * @return Php\Entity\Statement[]
	 */
	private function parseText($text, $dirs)
	{
		$Parser = new Parser\Text\Parser();
		return $Parser->parseText($text, $dirs);
	}

	/**
	 *
	 * @param type $files
	 * @param type $dirs
	 * @return type
	 */
	public function getSwagger($files, $dirs = [])
	{
		$dirs = array_merge($this->dirs, $dirs);

		$Statements = [];
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

			$Statements = array_merge($Statements, $fileStatements);
		}

		$Swagger = new Swagger\Swagger($this->host, $this->basePath);

		$stack = [$Swagger]; /* @var Swagger\AbstractObject[] $stack */
		foreach ($Statements as $Statement) {
			$top = end($stack);

			do {
				$result = $top->handleCommand($Statement->command, $Statement->data);

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
		}

		return $Swagger->toArray();
	}

}
