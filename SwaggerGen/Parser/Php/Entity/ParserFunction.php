<?php

namespace SwaggerGen\Parser\Php\Entity;

/**
 * Representation and parser of a PHP function or class method and all it's
 * comments.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ParserFunction extends AbstractEntity
{

	public $name = null;
	private $lastStatements = null;

	public function __construct(\SwaggerGen\Parser\Php\Parser $Parser, &$tokens, $Statements)
	{
		if ($Statements) {
			$this->Statements = array_merge($this->Statements, $Statements);
		}

		$depth = 0;

		$token = current($tokens);
		while ($token) {
			switch ($token[0]) {
				case T_STRING:
					if (empty($this->name)) {
						$this->name = $token[1];
					}
					break;

				case '{':
					++$depth;
					break;

				case '}':
					--$depth;
					if ($depth == 0) {
						if ($this->lastStatements) {
							$this->Statements = array_merge($this->Statements, $this->lastStatements);
							$this->lastStatements = null;
						}
						return;
					}
					break;

				case T_COMMENT:
					if ($this->lastStatements) {
						$this->Statements = array_merge($this->Statements, $this->lastStatements);
						$this->lastStatements = null;
					}
					$Statements = $Parser->tokenToStatements($token);
					$Parser->queueClassesFromComments($Statements);
					$this->Statements = array_merge($this->Statements, $Statements);
					break;

				case T_DOC_COMMENT:
					if ($this->lastStatements) {
						$this->Statements = array_merge($this->Statements, $this->lastStatements);
					}
					$Statements = $Parser->tokenToStatements($token);
					$Parser->queueClassesFromComments($Statements);
					$this->lastStatements = $Statements;
					break;
			}

			$token = next($tokens);
		}

		if ($this->lastStatements) {
			$this->Statements = array_merge($this->Statements, $this->lastStatements);
			$this->lastStatements = null;
		}
		return;
	}

	public function getStatements()
	{
		// inherit
		return $this->Statements;
	}

}
