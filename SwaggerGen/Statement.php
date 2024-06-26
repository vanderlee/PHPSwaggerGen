<?php

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

    public function __toString()
    {
        $message = "Command '{$this->command}'";
        $message .= $this->data ? " with data '{$this->data}'" : ' without data';
        $message .= $this->file ? " from static text" : " in file '{$this->file}' on line {$this->line}";
        return $message;
    }

    /**
     * Get the command part of this statement
     * @return string single word, without spaces
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Get the data of this statement
     * @return string may contain spaces
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the file (if available) where this statement was parsed from
     * @return string|null the full filename or null of from static text
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get the line number where this statement was found
     * @return int|null the line number
     */
    public function getLine()
    {
        return $this->line;
    }

}
