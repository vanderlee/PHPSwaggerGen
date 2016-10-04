<?php

namespace SwaggerGen\Parser\Php\Entity;

/**
 * Abstract foundation for all PHP entity parsers.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AbstractEntity
{

	/**
	 * @var \SwaggerGen\Statement[]
	 */
	public $Statements = array();

	/**
	 * Returns true if a statement with the specified command exists.
	 * @param string $command
	 * @return boolean
	 */
	public function hasCommand($command)
	{
		foreach ($this->Statements as $Statement) {
			if ($Statement->command === $command) {
				return true;
			}
		}

		return false;
	}

	public function getStatements()
	{
		return $this->Statements;
	}

}
