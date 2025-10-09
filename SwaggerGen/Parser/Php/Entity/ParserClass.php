<?php

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Parser\Php\Parser;

/**
 * Representation and parser of a PHP class or interface and all it's comments.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ParserClass extends AbstractEntity
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var ParserFunction[]
     */
    public $methods = [];

    /**
     * @var string
     */
    public $extends;

    /**
     * @var string[]
     */
    public $implements = [];
    private $lastStatements = [];

    public function __construct(Parser $Parser, &$tokens, $statements)
    {
        $lastStatements = [];

        if ($statements) {
            $this->statements = array_merge($this->statements, $statements);
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
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case T_STRING_VARNAME:
                    $mode = null;
                    ++$depth;
                    break;

                case '}':
                    --$depth;
                    if ($depth === 0) {
                        array_push($this->statements, ...$lastStatements);
                        return;
                    }
                    break;

                case T_FUNCTION:
                    $Method = new ParserFunction($Parser, $tokens, $lastStatements);
                    $this->methods[strtolower($Method->name)] = $Method;
                    $lastStatements = [];
                    break;

                case T_EXTENDS:
                    $mode = T_EXTENDS;
                    break;

                case T_IMPLEMENTS:
                    $mode = T_IMPLEMENTS;
                    break;

                case T_COMMENT:
                    array_push($this->statements, ...$lastStatements);
                    $lastStatements = [];
                    $statements = $Parser->tokenToStatements($token);
                    $Parser->queueClassesFromComments($statements);
                    array_push($this->statements, ...$statements);
                    break;

                case T_DOC_COMMENT:
                    array_push($this->statements, ...$lastStatements);
                    $statements = $Parser->tokenToStatements($token);
                    $Parser->queueClassesFromComments($statements);
                    $lastStatements = $statements;
                    break;
            }

            $token = next($tokens);
        }

        array_push($this->statements, ...$lastStatements);
    }
}
