<?php

namespace SwaggerGen\Parser\Text;

/**
 * Parses lines in text content as command statements.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parser implements \SwaggerGen\Parser\IParser
{

	protected $common_dirs = [];

	public function __construct(Array $dirs = [])
	{
		foreach ($dirs as $dir) {
			$this->common_dirs[] = realpath($dir);
		}
	}

	public function addDirs(Array $dirs)
	{
		foreach ($dirs as $dir) {
			$this->common_dirs[] = realpath($dir);
		}
	}

	public function parse($file, Array $dirs = [])
	{
		return $this->parseText(file_get_contents(realpath($file)), $dirs);
	}

	public function parseText($text, Array $dirs = [])
	{
		$this->dirs = $this->common_dirs;
		foreach ($dirs as $dir) {
			$this->dirs[] = realpath($dir);
		}

		$Statements = [];

		foreach (preg_split('/\\R/m', $text) as $line) {
			$line = trim($line);
			$command = \SwaggerGen\Util::words_shift($line);
			if (!empty($command)) {
				$Statements[] = new \SwaggerGen\Statement($command, $line);
			}
		}

		return $Statements;
	}

}
