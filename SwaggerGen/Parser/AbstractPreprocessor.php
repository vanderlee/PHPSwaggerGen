<?php

namespace SwaggerGen\Parser;

/**
 * Generic preprocessor statement handler.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractPreprocessor
{

    private $defines = [];
    private $stack = [];

    public function __construct()
    {
        $this->resetDefines();
    }

    public function resetDefines(): void
    {
        $this->defines = [];
    }

    public function addDefines(array $defines): void
    {
        $this->defines = array_merge($this->defines, $defines);
    }

    public function define($name, $value = 1): void
    {
        $this->defines[$name] = $value;
    }

    public function undefine($name): void
    {
        unset($this->defines[$name]);
    }

    public function preprocessFile($filename)
    {
        return $this->preprocess(file_get_contents($filename));
    }

    public function preprocess($content)
    {
        $this->stack = [];

        return $this->parseContent($content);
    }

    abstract protected function parseContent($content);

    protected function handle($command, $expression): bool
    {
        switch (strtolower($command)) {
            case 'if':
                $name = self::wordShift($expression);
                $state = $this->getState();
                if (empty($expression)) {
                    $this->stack[] = $state && !empty($this->defines[$name]);
                } else {
                    $this->stack[] = $state && isset($this->defines[$name]) && $this->defines[$name] == $expression;
                }
                break;

            case 'ifdef':
                $this->stack[] = $this->getState() && isset($this->defines[$expression]);
                break;

            case 'ifndef':
                $this->stack[] = $this->getState() && !isset($this->defines[$expression]);
                break;

            case 'else':
                $state = $this->getState();
                array_pop($this->stack);
                $this->stack[] = !$state;
                break;

            case 'elif':
                $name = self::wordShift($expression);
                $state = $this->getState();
                array_pop($this->stack);
                if (empty($expression)) {
                    $this->stack[] = !$state && !empty($this->defines[$name]);
                } else {
                    $this->stack[] = !$state && isset($this->defines[$name]) && $this->defines[$name] == $expression;
                }
                break;

            case 'define':
                $name = self::wordShift($expression);
                $this->defines[$name] = $expression;
                break;

            case 'undef':
                unset($this->defines[$expression]);
                break;

            case 'endif':
                array_pop($this->stack);
                break;

            default:
                return false;
        }

        return true;
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

    protected function getState(): bool
    {
        return empty($this->stack) || end($this->stack);
    }
}
