<?php

namespace SwaggerGen\Parser\Text;

use SwaggerGen\Parser\AbstractPreprocessor;
use SwaggerGen\Parser\IParser;
use SwaggerGen\Statement;

/**
 * Parses lines in text content as command statements.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Parser implements IParser
{

    /**
     * List of directories to scan for class files referenced in the parsed
     * command statements.
     *
     * @var string[]
     */
    protected $common_dirs = [];

    /**
     * @var AbstractPreprocessor
     */
    private $Preprocessor;

    /**
     * Create a new text parser and set directories to scan for referenced
     * class files.
     *
     * @param string[] $dirs
     */
    public function __construct(array $dirs = [])
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }

        $this->Preprocessor = new Preprocessor();
    }

    /**
     * Add additional directories to scan for referenced class files.
     *
     * @param string[] $dirs
     */
    public function addDirs(array $dirs): void
    {
        foreach ($dirs as $dir) {
            $this->common_dirs[] = realpath($dir);
        }
    }

    /**
     * Parse a text file
     *
     * @param string $file
     * @param string[] $dirs
     * @param string[] $defines
     * @return Statement[]
     */
    public function parse($file, array $dirs = [], array $defines = []): array
    {
        return $this->parseText(file_get_contents(realpath($file)), $dirs);
    }

    /**
     * Parse plain text
     *
     * @param string $text
     * @param string[] $dirs
     * @param string[] $defines
     * @return Statement[]
     */
    public function parseText($text, array $dirs = [], array $defines = []): array
    {
        $this->Preprocessor->resetDefines();
        $this->Preprocessor->addDefines($defines);
        $text = $this->Preprocessor->preprocess($text);

        $Statements = [];

        foreach (preg_split('/\\R/m', $text) as $line => $data) {
            $data = trim($data);
            $command = self::wordShift($data);
            if (!empty($command)) {
                $Statements[] = new Statement($command, $data, null, $line);
            }
        }

        return $Statements;
    }

    /**
     * Get the first word from a string and remove it from the string.
     *
     * @param string $data
     * @return boolean|string
     */
    private static function wordShift(&$data)
    {
        if (preg_match('~^(\S+)\s*(.*)$~', $data, $matches) === 1) {
            $data = $matches[2];
            return $matches[1];
        }
        return false;
    }

}
