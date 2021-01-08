<?php
declare(strict_types=1);

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Parser\Php\Parser;
use SwaggerGen\Statement;

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

    /**
     * ParserClass constructor.
     *
     * @param Parser           $parser
     * @param string[][]       $tokens
     * @param Statement[]|null $statements
     */
    public function __construct(Parser $parser, array &$tokens, array $statements = null)
    {
        $this->addStatements($statements);

        $lastStatements = [];
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
                            $parser->queueClass($token[1]);
                            $this->extends = $token[1];
                            $mode = null;
                            break;

                        case T_IMPLEMENTS:
                            $parser->queueClass($token[1]);
                            $this->implements[] = $token[1];
                            break;

                        default:
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
                        $this->addStatements($lastStatements);

                        return;
                    }
                    break;

                case T_FUNCTION:
                    $Method = new ParserFunction($parser, $tokens, $lastStatements);
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
                    $this->addStatements($lastStatements);
                    if (!empty($lastStatements)) {
                        $lastStatements = null;
                    }
                    $statements = $parser->tokenToStatements($token);
                    $parser->queueClassesFromComments($statements);
                    $this->addStatements($statements);
                    break;

                case T_DOC_COMMENT:
                    $this->addStatements($lastStatements);
                    $statements = $parser->tokenToStatements($token);
                    $parser->queueClassesFromComments($statements);
                    $lastStatements = $statements;
                    break;

                default:
                    break;
            }

            $token = next($tokens);
        }

        $this->addStatements($lastStatements);
    }
}
