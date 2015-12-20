<?php

namespace SwaggerGen;

/**
 * Represents a command statement, which links the code parsers to the document
 * generator side of SwaggerGen.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Statement
{

	public $command;
	public $data;

	public function __construct($command, $data)
	{
		$this->command = $command;
		$this->data = $data;
	}

	public function __toString()
	{
		$message = "PHP comment command '{$this->command}'";
		$message .= $this->data ? " with data '{$this->data}'" : ' without data';
		return $message;
	}

}
