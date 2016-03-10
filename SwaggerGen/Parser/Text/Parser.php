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

	protected $common_dirs = array();

	public function __construct(Array $dirs = array())
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

	public function parse($file, Array $dirs = array())
	{
		return $this->parseText(file_get_contents(realpath($file)), $dirs);
	}

	public function parseText($text, Array $dirs = array())
	{
		$this->dirs = $this->common_dirs;
		foreach ($dirs as $dir) {
			$this->dirs[] = realpath($dir);
		}

		$Statements = array();

		foreach (preg_split('/\\R/m', $text) as $line) {
			$line = trim($line);
			$command = self::wordShift($line);
			if (!empty($command)) {
				$Statements[] = new \SwaggerGen\Statement($command, $line);
			}
		}

		return $Statements;
	}

	private static function wordShift(&$data)
	{
		if (preg_match('~^(\S+)\s*(.*)$~', $data, $matches) === 1) {
			$data = $matches[2];
			return $matches[1];
		}
		return false;
	}

}
