<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

/**
 * Describes a Swagger Path object, containing any number of operations
 * belonging to a single endpoint defined by this class.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Path extends AbstractObject
{

    private static $methods = array(
        'get',
        'put',
        'post',
        'delete',
        'options',
        'head',
        'patch',
    );

    /**
     * @var Operation[] $operation
     */
    private $operations = [];

    /**
     * @var Tag|null $tag ;
     */
    private $tag;

    public function __construct(AbstractObject $parent, ?Tag $Tag = null)
    {
        parent::__construct($parent);
        $this->tag = $Tag;
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'method': // alias
            case 'operation':
                $method = strtolower(self::wordShift($data));

                if (!in_array($method, self::$methods)) {
                    throw new Exception('Unrecognized operation method \'' . $method . '\'');
                }

                if (isset($this->operations[$method])) {
                    $Operation = $this->operations[$method];
                } else {
                    $summary = $data;
                    $Operation = new Operation($this, $summary, $this->tag);
                    $this->operations[$method] = $Operation;
                }

                return $Operation;

            case 'description':
                if ($this->tag) {
                    return $this->tag->handleCommand($command, $data);
                }
                break;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        $methods = self::$methods;
        uksort($this->operations, static function ($a, $b) use ($methods) {
            return array_search($a, $methods) - array_search($b, $methods);
        });

        return self::arrayFilterNull(array_merge(
            self::objectsToArray($this->operations)
            , parent::toArray()));
    }

    public function __toString()
    {
        end($this->operations);
        return __CLASS__ . ' ' . key($this->operations);
    }

    /**
     * Check if an operation with the given id is registered to this Path.
     *
     * @param string $operationId
     * @return boolean
     */
    public function hasOperationId($operationId)
    {
        foreach ($this->operations as $operation) {
            if ($operation->getId() === $operationId) {
                return true;
            }
        }

        return false;
    }

}
