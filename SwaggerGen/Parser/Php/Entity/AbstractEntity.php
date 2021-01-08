<?php
declare(strict_types=1);

namespace SwaggerGen\Parser\Php\Entity;

use SwaggerGen\Statement;

/**
 * Abstract foundation for all PHP entity parsers.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AbstractEntity
{

    /**
     * @var Statement[]
     */
    protected $statements = [];

    /**
     * Returns true if a statement with the specified command exists.
     *
     * @param string $command
     *
     * @return boolean
     */
    public function hasCommand(string $command): bool
    {
        foreach ($this->statements as $Statement) {
            if ($Statement->getCommand() === $command) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Statement[]
     */
    public function getStatements(): array
    {
        return $this->statements;
    }

    /**
     * @param Statement[] $statements
     */
    protected function addStatements(array $statements = null): void
    {
        if (!empty($statements)) {
            array_push($this->statements, ...$statements);
        }
    }

}
