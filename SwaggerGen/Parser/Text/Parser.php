<?php

namespace SwaggerGen\Parser\Text;

/**
 * Parses lines in text content as command statements.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parser implements \SwaggerGen\Parser\IParser
{

	/**
	 * List of directories to scan for class files referenced in the parsed
	 * command statements.
	 *
	 * @var string[]
	 */
	protected $common_dirs = array();

	/**
	 * Create a new text parser and set directories to scan for referenced
	 * class files.
	 *
	 * @param string[] $dirs
	 */
	public function __construct(Array $dirs = array())
	{
		foreach ($dirs as $dir) {
			$this->common_dirs[] = realpath($dir);
		}
	}

	/**
	 * Add additional directories to scan for referenced class files.
	 *
	 * @param string[] $dirs
	 */
	public function addDirs(Array $dirs)
	{
		foreach ($dirs as $dir) {
			$this->common_dirs[] = realpath($dir);
		}
	}

	/**
	 * Parse a text file
	 *
	 * @param string $file
	 * @param string[] $dirs
	 * @return \SwaggerGen\Statement[]
	 */
	public function parse($file, Array $dirs = array())
	{
		return $this->parseText(file_get_contents(realpath($file)), $dirs);
	}

	/**
	 * Parse plain text
	 *
	 * @param string $text
	 * @param string[] $dirs
	 * @return \SwaggerGen\Statement
	 */
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

	/**
	 * Get the first word from a string and remove it from the string.
	 *
	 * @param string $data
	 * @return boolean|string
	 */
	private static function wordShift(&$data)
	{
		if (preg_match('~^(\S+)\s*(.*)$~', $data, $matches) === 1) {
			$data = $matches[2];
			return $matches[1];
		}
		return false;
	}

}
