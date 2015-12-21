<?php

namespace SwaggerGen\Parser\Php;

/**
 * Represents a PHP-specific command statement, keeping track of context for
 * debugging purposes.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Statement extends \SwaggerGen\Statement
{

	public $command;
	public $data;
	public $file;
	public $line;

	public function __construct($command, $data, $file, $line)
	{
		$this->command = $command;
		$this->data = $data;
		$this->file = $file;
		$this->line = $line;
	}

	public function __toString()
	{
		$message = 'PHP ' . parent::__toString() . " in line {$this->line}";
		$message .= $this->file ? " of file '{$this->file}'." : ' of code string';
		return $message;
	}

}
