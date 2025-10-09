<?php

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Statement;

/**
 * Abstract foundation for all PHP entity parsers.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AbstractEntity
{

    /**
     * @var Statement[]
     */
    public $statements = [];

    /**
     * Returns true if a statement with the specified command exists.
     * @param string $command
     * @return boolean
     */
    public function hasCommand(string $command): bool
    {
        foreach ($this->statements as $statement) {
            if ($statement->getCommand() === $command) {
                return true;
            }
        }

        return false;
    }

    public function getStatements(): array
    {
        return $this->statements;
    }
}
