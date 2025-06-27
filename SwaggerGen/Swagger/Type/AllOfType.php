<?php

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;

/**
 * allOf compound type
 *
 * @package    SwaggerGen
 * @author     Bruce Weirdan <weirdan@gmail.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AllOfType extends AbstractType
{
    private $allOfItems = array();
    private $mostRecentItem;

    /**
     * @throws Exception
     */
    protected function parseDefinition($definition)
    {
        $pattern = self::REGEX_START . 'allof' . self::REGEX_CONTENT . self::REGEX_END;
        $inlineDef = '';
        if (preg_match($pattern, $definition, $matches)
            && isset($matches[1])) {
            $inlineDef = $matches[1];
        }
        if ($inlineDef) {
            foreach (self::parseList($inlineDef) as $item) {
                $this->handleCommand('item', $item);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        if (strtolower($command) === 'item') {
            $this->mostRecentItem = self::typeFactory($this, $data);
            $this->allOfItems[] = $this->mostRecentItem;
            return $this;
        }
        if (isset($this->mostRecentItem)
            && $this->mostRecentItem->handleCommand($command, $data)) {
            return $this;
        }
        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        $allOf = array();
        foreach ($this->allOfItems as $item) {
            $allOf[] = $item->toArray();
        }
        return self::arrayFilterNull(array_merge(array(
            'allOf' => $allOf,
        ), parent::toArray()));
    }
}
