<?php
declare(strict_types=1);

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Parser\Php\Parser;
use SwaggerGen\Statement;

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

    /**
     * @var string
     */
    public $name;

    /**
     * @var Statement[]
     */
    private $lastStatements = [];

    /**
     * ParserFunction constructor.
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

        $token = current($tokens);
        while ($token) {
            switch ($token[0]) {
                case T_STRING:
                    if (empty($this->name)) {
                        $this->name = $token[1];
                    }
                    break;

                case '{':
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case T_STRING_VARNAME:
                    ++$depth;
                    break;

                case '}':
                    --$depth;
                    if ($depth === 0) {
                        $this->addStatements($lastStatements);
                        return;
                    }
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
