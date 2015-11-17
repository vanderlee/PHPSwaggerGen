<?php

namespace SwaggerGen\Parser\Php\Entity;

/**
 * Representation and parser of a PHP class or interface and all it's comments.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ParserClass extends AbstractEntity
{

	/**
	 * @var string
	 */
	public $name = null;

	/**
	 * @var ParserFunction[]
	 */
	public $Methods = [];

	/**
	 * @var string
	 */
	public $extends = null;

	/**
	 * @var string[]
	 */
	public $implements = [];
	private $lastStatements = null;

	public function __construct(\SwaggerGen\Parser\Php\Parser $Parser, &$tokens, $Statements)
	{
		if ($Statements) {
			$this->Statements = array_merge($this->Statements, $Statements);
		}

		$depth = 0;

		$mode = T_CLASS;

		$token = current($tokens);
		while ($token) {
			switch ($token[0]) {
				case T_STRING:
					switch ($mode) {
						case T_CLASS:
							$this->name = $token[1];
							$mode = null;
							break;

						case T_EXTENDS:
							$Parser->queueClass($token[1]);
							$this->extends = $token[1];
							$mode = null;
							break;

						case T_IMPLEMENTS:
							$Parser->queueClass($token[1]);
							$this->implements[] = $token[1];
							break;
					}
					break;

				case '{':
					$mode = null;
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

				case T_FUNCTION:
					$Method = new ParserFunction($Parser, $tokens, $this->lastStatements);
					$this->Methods[strtolower($Method->name)] = $Method;
					$this->lastStatements = null;
					break;

				case T_EXTENDS:
					$mode = T_EXTENDS;
					break;

				case T_IMPLEMENTS:
					$mode = T_IMPLEMENTS;
					break;

				case T_COMMENT:
					if ($this->lastStatements) {
						$this->Statements = array_merge($this->Statements, $this->lastStatements);
						$this->lastStatements = null;
					}
					$Statements = $Parser->commentToStatements($token[1]);
					$Parser->queueClassesFromComments($Statements);
					$this->Statements = array_merge($this->Statements, $Statements);
					break;

				case T_DOC_COMMENT:
					if ($this->lastStatements) {
						$this->Statements = array_merge($this->Statements, $this->lastStatements);
					}
					$Statements = $Parser->commentToStatements($token[1]);
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

}
