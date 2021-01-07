<?php
declare(strict_types=1);

namespace SwaggerGen;

/**
 * Represents a command statement, which links the code parsers to the document
 * generator side of SwaggerGen.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Statement
{

    private $command;
    private $data;
    private $file;
    private $line;

    public function __construct($command, $data = null, $file = null, $line = null)
    {
        $this->command = $command;
        $this->data = $data;
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ("Command '{$this->command}'")
            . ($this->data
                ? " with data '{$this->data}'"
                : ' without data')
            . ($this->file
                ? " from static text"
                : " in file '{$this->file}' on line {$this->line}");
    }

    /**
     * Get the command part of this statement
     *
     * @return string single word, without spaces
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Get the data of this statement
     *
     * @return string may contain spaces
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Get the file (if available) where this statement was parsed from
     *
     * @return string|null the full filename or null of from static text
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * Get the line number where this statement was found
     *
     * @return int|null the line number
     */
    public function getLine(): ?int
    {
        return $this->line;
    }

}
