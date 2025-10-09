<?php

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Parser\Php\Parser;

/**
 * Representation and parser of a PHP function or class method and all it's
 * comments.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ParserFunction extends AbstractEntity
{
    public string|null $name = null;

    public function __construct(Parser $Parser, &$tokens, array $statements = null)
    {
        $lastStatements = [];

        if (!empty($statements)) {
            $this->statements = array_merge($this->statements, $statements);
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
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case T_STRING_VARNAME:
                    ++$depth;
                    break;

                case '}':
                    --$depth;
                    if ($depth === 0) {
                        array_push($this->statements, ...$lastStatements);
                        return;
                    }
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
