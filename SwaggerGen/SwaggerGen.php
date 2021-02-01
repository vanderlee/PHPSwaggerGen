<?php
declare(strict_types=1);

namespace SwaggerGen;

use Exception as ExceptionAlias;
use SwaggerGen\Parser\Text\Parser as TextParser;
use SwaggerGen\Swagger\AbstractObject;
use SwaggerGen\Swagger\Swagger;

/**
 * Main class of SwaggerGen which serves as a facade for the entire library.
 *
 * Copyright (c) 2014-2015 Martijn van der Lee
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class SwaggerGen
{

    public const FORMAT_ARRAY = '';
    public const FORMAT_JSON = 'json';
    public const FORMAT_JSON_PRETTY = 'json+';
    public const FORMAT_YAML = 'yaml';

    private $host;
    private $basePath;
    private $dirs;
    private $defines = [];

    /**
     * @var TypeRegistry
     */
    private $typeRegistry;

    /**
     * Create a new SwaggerGen instance
     *
     * @param string       $host
     * @param string       $basePath
     * @param string[]     $dirs
     * @param TypeRegistry $typeRegistry
     */
    public function __construct($host = '', $basePath = '', $dirs = [], $typeRegistry = null)
    {
        $this->host = $host;
        $this->basePath = $basePath;
        $this->dirs = $dirs;
        $this->typeRegistry = $typeRegistry;
    }

    /**
     * Set a new type registry
     *
     * @param TypeRegistry $typeRegistry
     */
    public function setTypeRegistry($typeRegistry = null): void
    {
        $this->typeRegistry = $typeRegistry;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function define(string $name, $value = 1): void
    {
        $this->defines[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function undefine(string $name): void
    {
        unset($this->defines[$name]);
    }

    /**
     * @param string   $file
     * @param string[] $dirs
     *
     * @return Statement[]
     * @throws Exception
     */
    private function parsePhpFile(string $file, array $dirs): array
    {
        $Parser = new Parser\Php\Parser();

        return $Parser->parse($file, $dirs, $this->defines);
    }

    /**
     * @param string $file
     *
     * @return Statement[]
     */
    private function parseTextFile(string $file): array
    {
        $Parser = new TextParser();

        return $Parser->parse($file, $this->defines);
    }

    /**
     * @param string $text
     *
     * @return Statement[]
     */
    private function parseText(string $text): array
    {
        $Parser = new TextParser();

        return $Parser->parseText($text, $this->defines);
    }

    /**
     * Creates Swagger\Swagger object and populates it with statements
     *
     * This effectively converts the linear list of statements into parse-tree
     * like structure, performing some checks (like rejecting unknown
     * subcommands) during the process. Returned Swagger\Swagger object is
     * ready for serialization with {@see Swagger::toArray}
     *
     * @param string      $host
     * @param string      $basePath
     * @param Statement[] $statements
     *
     * @return Swagger
     * @throws StatementException
     */
    public function parseStatements(string $host, string $basePath, array $statements): Swagger
    {
        $swagger = new Swagger($host, $basePath, $this->typeRegistry);

        /* @var AbstractObject[] $stack */
        $stack = [
            $swagger,
        ];
        foreach ($statements as $statement) {
            try {
                $top = end($stack);

                do {
                    $result = $top->handleCommand($statement->getCommand(), $statement->getData());

                    if ($result) {
                        $stack = self::pushToTop($stack, $result);

                        break;
                    }

                    $top = prev($stack);
                } while ($top);
            } catch (ExceptionAlias $e) {
                throw new StatementException($e->getMessage(), $e->getCode(), $e, $statement);
            }

            if (!$result && !$top) {
                throw new StatementException(implode('. ', [
                    sprintf('Unsupported or unknown command: %s %s', $statement->getCommand(), $statement->getData()),
                    self::buildStacktrace($stack),
                ]), 0, null, $statement);
            }
        }

        return $swagger;
    }

    /**
     * Remove all similar classes from the stack and push the stack item to the top of the stack
     *
     * @param array  $stack
     * @param object $item
     *
     * @return array
     */
    public static function pushToTop(array $stack, object $item): array
    {
        if ($item === end($stack)) {
            return $stack;
        }

        $stack = array_filter($stack, static function ($class) use ($item) {
            return !($class instanceof $item);
        });

        $stack[] = $item;

        return $stack;
    }

    /**
     * @param string[] $stack
     *
     * @return string
     */
    private static function buildStacktrace(array $stack): string
    {
        $stacktrace = [];

        foreach ($stack as $object) {
            $stacktrace[] = (string)$object;
        }

        return implode(", \n", $stacktrace);
    }

    /**
     * Get Swagger 2.x output
     *
     * @param string[] $files
     * @param string[] $dirs
     * @param string   $format
     *
     * @return string|array
     * @throws Exception
     * @throws StatementException
     */
    public function getSwagger(array $files, array $dirs = [], string $format = self::FORMAT_ARRAY)
    {
        $dirs = array_merge($this->dirs, $dirs);

        $statements = [];
        foreach ($files as $file) {
            switch (pathinfo($file, PATHINFO_EXTENSION)) {
                case 'php':
                    $fileStatements = $this->parsePhpFile($file, $dirs);
                    break;

                case 'txt':
                    $fileStatements = $this->parseTextFile($file);
                    break;

                default:
                    $fileStatements = $this->parseText($file);
                    break;
            }

            $statements = array_merge($statements, $fileStatements);
        }

        $output = $this->parseStatements($this->host, $this->basePath, $statements)->toArray();

        switch ($format) {
            case self::FORMAT_JSON:
                $output = json_encode($output);
                break;

            case self::FORMAT_JSON_PRETTY:
                $flags = (defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0); // Since PHP 5.4.0
                $output = json_encode($output, $flags);
                break;

            case self::FORMAT_YAML:
                if (!function_exists('yaml_emit')) {
                    throw new Exception('YAML extension not installed.');
                }
                array_walk_recursive($output, static function (&$value) {
                    if (is_object($value)) {
                        $value = (array)$value;
                    }
                });
                $output = yaml_emit($output, YAML_UTF8_ENCODING, YAML_LN_BREAK);
                break;

            default:
        }

        return $output;
    }

}
