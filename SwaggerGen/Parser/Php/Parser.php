<?php
declare(strict_types=1);

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
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parser extends AbstractEntity implements IParser
{

    private const COMMENT_TAG = 'rest';

// transient

    private $current_file;
    private $files_queued = [];
    private $files_done = [];
    private $dirs = [];
// States

    /**
     * @var Statement[]
     */
    private $lastStatements = [];

    /**
     * @var ParserClass[]
     */
    public $classes = [];

    /**
     * @var ParserFunction[]
     */
    public $functions = [];

    /**
     * @var AbstractPreprocessor
     */
    private $Preprocessor;

    /**
     * Directories available to all parse calls
     *
     * @var string[]
     */
    protected $common_dirs = [];

    /**
     * @param array $dirs
     */
    public function __construct(array $dirs = [])
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }

        $this->Preprocessor = new Preprocessor(self::COMMENT_TAG);
    }

    /**
     * @param array $dirs
     */
    public function addDirs(array $dirs): void
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }
    }

    /*
     * @return Statement[]
     */
    private function extractStatements(): array
    {
        // Core comments
        $statements = $this->statements;

        // Functions
        foreach ($this->functions as $function) {
            if ($function->hasCommand('method')) {
                array_push($statements, ...$function->statements);
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

    /**
     * @param       $file
     * @param array $dirs
     * @param array $defines
     *
     * @return Statement[]
     * @throws Exception
     */
    public function parse($file, array $dirs = [], array $defines = []): array
    {
        $this->dirs = $this->common_dirs;
        foreach ($dirs as $dir) {
            $this->dirs[] = realpath($dir);
        }

        $this->parseFiles([$file], $defines);

        // Inherit classes
        foreach ($this->classes as $class) {
            $this->inherit($class);
        }

        // Expand functions with used and seen functions/methods.
        foreach ($this->classes as $class) {
            foreach ($class->methods as $method) {
                $method->statements = $this->expand($method->statements, $class);
            }
        }

        return $this->extractStatements();
    }

    /**
     * Convert a T_*_COMMENT string to an array of Statements
     *
     * @param string[] $token
     *
     * @return Statement[]
     */
    public function tokenToStatements(array $token): array
    {
        $comment = $token[1];
        $commentLineNumber = (int)$token[2];
        $commentLines = [];

        $match = [];
        if (preg_match('~^/\*\*?\s*(.*)\s*\*/$~sm', $comment, $match) === 1) {
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
            if ($command !== null && chr(ord($line)) === '@') {
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
                    $data .= preg_replace('~\s*\**/\s*$~', '', $line);
                }
            }
        }

        if ($command !== null) {
            $statements[] = new Statement($command, $data, $this->current_file, $commentLineNumber + $commandLineNumber);
        }

        return $statements;
    }

    public function queueClass($classname): void
    {
        foreach ($this->dirs as $dir) {
            $paths = [
                $dir . DIRECTORY_SEPARATOR . $classname . '.php',
                $dir . DIRECTORY_SEPARATOR . $classname . '.class.php',
            ];

            foreach ($paths as $path) {
                $realpath = realpath($path);
                if (in_array($realpath, $this->files_done, false)) {
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
     * Add to the queue any classes based on the commands.
     *
     * @param Statement[] $Statements
     */
    public function queueClassesFromComments(array $Statements): void
    {
        foreach ($Statements as $Statement) {
            if (in_array($Statement->getCommand(), ['uses', 'see'])) {
                $match = [];
                if ((preg_match('~^(\w+)(::|->)?(\w+)?(?:\(\))?$~', $Statement->getData(), $match) === 1)
                    && !in_array($match[1], ['self', '$this'])) {

                    $this->queueClass($match[1]);
                }
            }
        }
    }

    private function parseTokens($source): void
    {
        $tokens = token_get_all($source);
        $token = reset($tokens);
        while ($token) {
            switch ($token[0]) {
                case T_CLASS:
                case T_INTERFACE:
                    $Class = new Entity\ParserClass($this, $tokens, $this->lastStatements);
                    $this->classes[strtolower($Class->name)] = $Class;
                    $this->lastStatements = null;
                    break;

                case T_FUNCTION:
                    $Function = new Entity\ParserFunction($this, $tokens, $this->lastStatements);
                    $this->functions[strtolower($Function->name)] = $Function;
                    $this->lastStatements = null;
                    break;

                case T_COMMENT:
                    $this->addStatements($this->lastStatements);
                    if (!empty($this->lastStatements)) {
                        $this->lastStatements = null;
                    }
                    $Statements = $this->tokenToStatements($token);
                    $this->queueClassesFromComments($Statements);
                    $this->statements = array_merge($this->statements, $Statements);
                    break;

                case T_DOC_COMMENT:
                    $this->addStatements($this->lastStatements);
                    $Statements = $this->tokenToStatements($token);
                    $this->queueClassesFromComments($Statements);
                    $this->lastStatements = $Statements;
                    break;

                default:
                    break;
            }

            $token = next($tokens);
        }
    }

    private function parseFiles(array $files, array $defines = []): void
    {
        $this->files_queued = $files;

        while (($file = array_shift($this->files_queued)) !== null) {
            $file = realpath($file);

            // @todo Test if this works
            if (in_array($file, $this->files_done, true)) {
                continue;
            }

            $this->current_file = $file;
            $this->files_done[] = $file;

            $this->Preprocessor->resetDefines();
            $this->Preprocessor->addDefines($defines);
            $source = $this->Preprocessor->preprocessFile($file);

            $this->parseTokens($source);

            $this->addStatements($this->lastStatements);
            if (!empty($this->lastStatements)) {
                $this->lastStatements = [];
            }
        }

        $this->current_file = null;
    }

    /**
     * Inherit the statements
     *
     * @param ParserClass $Class
     */
    private function inherit(Entity\ParserClass $Class): void
    {
        $inherits = array_merge([$Class->extends], $Class->implements);
        while (($inherit = array_shift($inherits)) !== null) {
            if (isset($this->classes[strtolower($inherit)])) {
                $inheritedClass = $this->classes[strtolower($inherit)];
                $this->inherit($inheritedClass);

                foreach ($inheritedClass->methods as $name => $Method) {
                    if (!isset($Class->methods[$name])) {
                        $Class->methods[$name] = $Method;
                    }
                }
            }
        }
    }

    /**
     * Expands a set of comments with comments of methods referred to by
     * rest\uses statements.
     *
     * @param Statement[]      $statements
     * @param ParserClass|null $self
     *
     * @return array
     * @throws Exception
     */
    private function expand(array $statements, ParserClass $self = null): array
    {
        $output = [];

        $match = null;
        foreach ($statements as $Statement) {
            if (in_array($Statement->getCommand(), ['uses', 'see'])) {
                if (preg_match('/^((?:\\w+)|\$this)(?:(::|->)(\\w+))?(?:\\(\\))?$/', strtolower($Statement->getData()), $match) === 1) {
                    if (count($match) >= 3) {
                        $class = null;
                        if (in_array($match[1], ['$this', 'self', 'static'])) {
                            $class = $self;
                        } elseif (isset($this->classes[$match[1]])) {
                            $class = $this->classes[$match[1]];
                        }

                        if ($class) {
                            if (isset($class->methods[$match[3]])) {
                                $method = $class->methods[$match[3]];
                                $method->statements = $this->expand($method->statements, $class);
                                array_push($output, ...$method->statements);
                            } else {
                                throw new Exception("Method '{$match[3]}' for class '{$match[1]}' not found");
                            }
                        } else {
                            throw new Exception("Class '{$match[1]}' not found");
                        }
                    } elseif (isset($this->functions[$match[1]])) {
                        $function = $this->functions[$match[1]];
                        $function->statements = $this->expand($function->statements);
                        array_push($output, ...$function->statements);
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

}
