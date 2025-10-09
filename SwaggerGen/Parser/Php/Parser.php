<?php

namespace SwaggerGen\Parser\Php;

use SwaggerGen\Exception;
use SwaggerGen\Parser\AbstractPreprocessor;
use SwaggerGen\Parser\IParser;
use SwaggerGen\Parser\Php\Entity\AbstractEntity;
use SwaggerGen\Parser\Php\Entity\ParserClass;
use SwaggerGen\Parser\Php\Entity\ParserFunction;
use SwaggerGen\Statement;

/**
 * Parses comments in PHP into a structure of functions, classes and methods,
 * resolving inheritance, references and namespaces.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parser extends AbstractEntity implements IParser
{

    const COMMENT_TAG = 'rest';

// transient
    /** @var Statement[] */
    public $statements = [];
    /**
     * @var ParserClass[]
     */
    public $classes = [];
    /**
     * @var ParserFunction[]
     */
    public $Functions = [];
    /**
     * Directories available to all parse calls
     *
     * @var string[]
     */
    protected $common_dirs = [];
// States
    private $current_file;
    private $files_queued = [];
    private $files_done = [];
    private $dirs = [];
    /**
     * @var Statement[]|null
     */
    private $lastStatements = [];
    /**
     * @var AbstractPreprocessor
     */
    private $Preprocessor;

    public function __construct(array $dirs = [])
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }

        $this->Preprocessor = new Preprocessor(self::COMMENT_TAG);
    }

    public function addDirs(array $dirs): void
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }
    }

    /**
     * @throws Exception
     */
    public function parse($file, array $dirs = [], array $defines = []): array
    {
        $this->dirs = $this->common_dirs;
        foreach ($dirs as $dir) {
            $this->dirs[] = realpath($dir);
        }

        $this->parseFiles(array($file), $defines);

        // Inherit classes
        foreach ($this->classes as $Class) {
            $this->inherit($Class);
        }

        // Expand functions with used and seen functions/methods.
        foreach ($this->classes as $Class) {
            foreach ($Class->methods as $Method) {
                $Method->statements = $this->expand($Method->statements, $Class);
            }
        }

        return $this->extractStatements();
    }

    private function parseFiles(array $files, array $defines = []): void
    {
        $this->files_queued = $files;

        $index = 0;
        while (($file = array_shift($this->files_queued)) !== null) {
            $file = realpath($file);

            // @todo Test if this works
            if (in_array($file, $this->files_done, true)) {
                continue;
            }

            $this->current_file = $file;
            $this->files_done[] = $file;
            ++$index;

            $this->Preprocessor->resetDefines();
            $this->Preprocessor->addDefines($defines);
            $source = $this->Preprocessor->preprocessFile($file);

            $this->parseTokens($source);

            if ($this->lastStatements !== null) {
                array_push($this->statements, ...$this->lastStatements);
                $this->lastStatements = [];
            }
        }

        $this->current_file = null;
    }

    private function parseTokens($source): void
    {
        $mode = null;
        $namespace = '';

        $tokens = token_get_all($source);
        $token = reset($tokens);
        while ($token) {
            switch ($token[0]) {
                case T_NAMESPACE:
                    $mode = T_NAMESPACE;
                    break;

                case T_NS_SEPARATOR:
                case T_STRING:
                    if ($mode === T_NAMESPACE) {
                        $namespace .= $token[1];
                    }
                    break;

                case ';':
                    $mode = null;
                    break;

                case T_CLASS:
                case T_INTERFACE:
                    $Class = new Entity\ParserClass($this, $tokens, $this->lastStatements);
                    $this->classes[strtolower($Class->name)] = $Class;
                    $this->lastStatements = [];
                    break;

                case T_FUNCTION:
                    $Function = new ParserFunction($this, $tokens, $this->lastStatements);
                    $this->Functions[strtolower($Function->name)] = $Function;
                    $this->lastStatements = [];
                    break;

                case T_COMMENT:
                    array_push($this->statements, ...$this->lastStatements);
                    $this->lastStatements = [];
                    $statements = $this->tokenToStatements($token);
                    $this->queueClassesFromComments($statements);
                    array_push($this->statements, ...$statements);
                    break;

                case T_DOC_COMMENT:
                    array_push($this->statements, ...$this->lastStatements);
                    $statements = $this->tokenToStatements($token);
                    $this->queueClassesFromComments($statements);
                    $this->lastStatements = $statements;
                    break;
            }

            $token = next($tokens);
        }
    }

    /**
     * Convert a T_*_COMMENT string to an array of Statements
     * @param array $token
     * @return Statement[]
     */
    public function tokenToStatements($token): array
    {
        [, $comment, $commentLineNumber] = $token;
        $commentLines = [];

        $match = [];
        if (preg_match('~^/\*\*?\s*(.*)\s*\*\/$~sm', $comment, $match) === 1) {
            $lines = explode("\n", $match[0]);
            foreach ($lines as $line) {
                if ((preg_match('~^\s*\*?\s*(.*?)\s*$~', $line, $match) === 1)
                    && !empty($match[1])) {
                    $commentLines[] = trim($match[1]);
                }
            }
        } elseif (preg_match('~^//\s*(.*)$~', $comment, $match) === 1) {
            $commentLines[] = trim($match[1]);
        }
        // to commands
        $match = [];
        $command = null;
        $data = '';
        $commandLineNumber = 0;
        $statements = [];
        foreach ($commentLines as $lineNumber => $line) {
            // If new @-command, store any old and start new
            if ($command !== null
                && chr(ord($line)) === '@'
            ) {
                $statements[] = new Statement($command, $data, $this->current_file, $commentLineNumber + $commandLineNumber);
                $command = null;
                $data = '';
            }

            if (preg_match('~^@' . preg_quote(self::COMMENT_TAG, '~') . '\\\\([a-z][-a-z]*[?!]?)\\s*(.*)$~', $line, $match) === 1) {
                [, $command, $data] = $match;
                $commandLineNumber = $lineNumber;
            } elseif ($command !== null) {
                if ($lineNumber < count($commentLines) - 1) {
                    $data .= ' ' . $line;
                } else {
                    $data .= preg_replace('~\s*\**\/\s*$~', '', $line);
                }
            }
        }

        if ($command !== null) {
            $statements[] = new Statement($command, $data, $this->current_file, $commentLineNumber + $commandLineNumber);
        }

        return $statements;
    }

    /**
     * Add to the queue any classes based on the commands.
     * @param Statement[] $Statements
     */
    public function queueClassesFromComments(array $Statements): void
    {
        foreach ($Statements as $Statement) {
            if (in_array($Statement->getCommand(), array('uses', 'see'))) {
                $match = [];
                if ((preg_match('~^(\w+)(::|->)?(\w+)?(?:\(\))?$~', $Statement->getData(), $match) === 1)
                    && !in_array($match[1], array('self', '$this'))) {
                    $this->queueClass($match[1]);
                }
            }
        }
    }

    public function queueClass($classname): void
    {
        foreach ($this->dirs as $dir) {
            $paths = array(
                $dir . DIRECTORY_SEPARATOR . $classname . '.php',
                $dir . DIRECTORY_SEPARATOR . $classname . '.class.php',
            );

            foreach ($paths as $path) {
                $realpath = realpath($path);
                if (in_array($realpath, $this->files_done, true)) {
                    return;
                }

                if (is_file($realpath)) {
                    $this->files_queued[] = $realpath;
                    return;
                }
            }
        }

        // assume it's a class;
    }

    /**
     * Inherit the statements
     * @param ParserClass $class
     */
    private function inherit(ParserClass $class): void
    {
        $inherits = array_merge(array($class->extends), $class->implements);
        while (($inherit = array_shift($inherits)) !== null) {
            if (isset($this->classes[strtolower($inherit)])) {
                $inheritedClass = $this->classes[strtolower($inherit)];
                $this->inherit($inheritedClass);

                foreach ($inheritedClass->methods as $name => $Method) {
                    if (!isset($class->methods[$name])) {
                        $class->methods[$name] = $Method;
                    }
                }
            }
        }
    }

    /**
     * Expands a set of comments with comments of methods referred to by rest\uses statements.
     *
     * @param Statement[] $Statements
     * @return Statement[]
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    private function expand(array $Statements, ?ParserClass $Self = null): array
    {
        $output = [];

        $match = [];
        foreach ($Statements as $Statement) {
            if (in_array($Statement->getCommand(), array('uses', 'see'))) {
                if (preg_match('/^((?:\\w+)|\$this)(?:(::|->)(\\w+))?(?:\\(\\))?$/', strtolower($Statement->getData()), $match) === 1) {
                    if (count($match) >= 3) {
                        $Class = null;
                        if (in_array($match[1], array('$this', 'self', 'static'))) {
                            $Class = $Self;
                        } elseif (isset($this->classes[$match[1]])) {
                            $Class = $this->classes[$match[1]];
                        }

                        if ($Class) {
                            if (isset($Class->methods[$match[3]])) {
                                $Method = $Class->methods[$match[3]];
                                $Method->statements = $this->expand($Method->statements, $Class);
                                array_push($output, ...$Method->statements);
                            } else {
                                throw new Exception("Method '{$match[3]}' for class '{$match[1]}' not found");
                            }
                        } else {
                            throw new Exception("Class '{$match[1]}' not found");
                        }
                    } elseif (isset($this->Functions[$match[1]])) {
                        $Function = $this->Functions[$match[1]];
                        $Function->statements = $this->expand($Function->statements);
                        array_push($output, ...$Function->statements);
                    } else {
                        throw new Exception("Function '{$match[1]}' not found");
                    }
                }
            } else {
                $output[] = $Statement;
            }
        }

        return $output;
    }

    private function extractStatements(): array
    {
        // Core comments
        $statements = $this->statements;

        // Functions
        foreach ($this->Functions as $Function) {
            if ($Function->hasCommand('method')) {
                array_push($statements, ...$Function->statements);
            }
        }

        // Classes
        foreach ($this->classes as $class) {
            array_push($statements, ...$class->statements);
            foreach ($class->methods as $method) {
                if ($method->hasCommand('method')) {
                    array_push($statements, ...$method->statements);
                }
            }
        }

        return $statements;
    }

}
