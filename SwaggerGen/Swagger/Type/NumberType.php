<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;

/**
 * Basic decimal-point numeric type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class NumberType extends AbstractType
{

    public const REGEX_RANGE = '(?:([[<])(-?(?:\\d*\\.?\\d+|\\d+\\.\\d*))?,(-?(?:\\d*\\.?\\d+|\\d+\\.\\d*))?([\\]>]))?';
    public const REGEX_DEFAULT = '(?:=(-?(?:\\d*\\.?\\d+|\\d+\\.\\d*)))?';

    private static $formats = [
        'float'  => 'float',
        'double' => 'double',
    ];
    private $format;
    private $default;
    private $maximum;
    private $exclusiveMaximum;
    private $minimum;
    private $exclusiveMinimum;
    private $enum = [];
    private $multipleOf;

    /**
     * @param string $definition
     *
     * @throws Exception
     */
    protected function parseDefinition(string $definition): void
    {
        $match = [];
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new Exception("Unparseable number definition: '{$definition}'");
        }

        $this->parseFormat($definition, $match);
        $this->parseRange($definition, $match);
        $this->parseDefault($match);
    }

    /**
     * @param string   $definition
     * @param string[] $match
     *
     * @throws Exception
     */
    private function parseFormat(string $definition, array $match): void
    {
        $format = strtolower($match[1]);

        if (!isset(self::$formats[$format])) {
            throw new Exception("Not a number: '{$definition}'");
        }

        $this->format = self::$formats[$format];
    }

    /**
     * @param string   $definition
     * @param string[] $match
     *
     * @throws Exception
     */
    private function parseRange(string $definition, array $match): void
    {
        if (!empty($match[2])) {
            if ($match[3] === ''
                && $match[4] === '') {

                throw new Exception("Empty number range: '{$definition}'");
            }

            $this->exclusiveMinimum = isset($match[2])
                ? ($match[2] === '<')
                : null;

            $this->minimum = $match[3] === ''
                ? null
                : (float)$match[3];

            $this->maximum = $match[4] === ''
                ? null
                : (float)$match[4];

            $this->exclusiveMaximum = isset($match[5])
                ? ($match[5] === '>')
                : null;

            if ($this->minimum
                && $this->maximum
                && $this->minimum > $this->maximum) {

                self::swap($this->minimum, $this->maximum);
                self::swap($this->exclusiveMinimum, $this->exclusiveMaximum);
            }
        }
    }

    /**
     * @param string[] $match
     *
     * @throws Exception
     */
    private function parseDefault(array $match): void
    {
        $this->default = isset($match[6])
        && $match[6] !== ''
            ? $this->validateDefault($match[6])
            : null;
    }

    /**
     * @param string $command The comment command
     * @param string $data    Any data added after the command
     *
     * @return AbstractType|boolean
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'default':
                $this->default = $this->validateDefault($data);

                return $this;

            case 'enum':
                $words = self::wordSplit($data);
                $words = array_map(function ($word): float {
                    return $this->validateDefault($word);
                }, $words);
                $this->enum = array_merge($this->enum, $words);

                return $this;

            case 'step':
                if (($step = (float)$data) > 0) {
                    $this->multipleOf = $step;
                }

                return $this;

            default:
                break;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge([
            'type'             => 'number',
            'format'           => $this->format,
            'default'          => $this->default,
            'minimum'          => $this->minimum,
            'exclusiveMinimum' => ($this->exclusiveMinimum && !is_null($this->minimum)) ? true : null,
            'maximum'          => $this->maximum,
            'exclusiveMaximum' => ($this->exclusiveMaximum && !is_null($this->maximum)) ? true : null,
            'enum'             => $this->enum,
            'multipleOf'       => $this->multipleOf,
        ], parent::toArray()));
    }

    public function __toString(): string
    {
        return __CLASS__;
    }

    /**
     * @param $value
     *
     * @return float
     * @throws Exception
     */
    private function validateDefault(string $value): float
    {

        if (preg_match('~^-?(?:\\d*\\.?\\d+|\\d+\\.\\d*)$~', $value) !== 1) {
            throw new Exception("Invalid number default: '{$value}'");
        }

        if ($this->maximum
            && ((float)$value > $this->maximum
                || ($this->exclusiveMaximum
                    && (float)$value === $this->maximum))) {
            throw new Exception("Default number beyond maximum: '{$value}'");
        }

        if ($this->minimum
            && ((float)$value < $this->minimum
                || ($this->exclusiveMinimum
                    && (float)$value === $this->minimum))) {
            throw new Exception("Default number beyond minimum: '{$value}'");
        }

        return (float)$value;
    }

}
