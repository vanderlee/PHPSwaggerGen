<?php

namespace SwaggerGen;

/**
 * Exception class that can take a Statement
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class StatementException extends \Exception
{

	/**
	 * @var \SwaggerGen\Statement
	 */
	private $statement = null;

	/**
	 * 
	 * @param string $message
	 * @param int $code
	 * @param \Throwable $previous
	 * @param \SwaggerGen\Statement $statement
	 */
	public function __construct($message = "", $code = 0, $previous = null, $statement = null)
	{
		$this->statement = $statement;

		parent::__construct($message, $code, $previous);
	}
	
	public function getStatement()
	{
		return $this->statement;
	}

}
