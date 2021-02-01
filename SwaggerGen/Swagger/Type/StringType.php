<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger\Type;

/**
 * Basic strings type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class StringType extends AbstractType
{

    private static $formats = [
        'string'   => '',
        'byte'     => 'byte',
        'binary'   => 'binary',
        'password' => 'password',
        'enum'     => '',
    ];

    /**
     * Name of the type
     *
     * @var string
     */
    protected $format = '';
    protected $pattern = null;
    protected $default = null;
    protected $maxLength = null;
    protected $minLength = null;
    protected $enum = [];

    protected function parseDefinition(string $definition): void
    {
        $definition = self::trim($definition);

        $match = [];
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_DEFAULT . self::REGEX_END, $definition, $match) !== 1) {
            throw new \SwaggerGen\Exception("Unparseable string definition: '{$definition}'");
        }

        $this->parseFormat($definition, $match);
        $this->parseContent($definition, $match);
        $this->parseRange($definition, $match);
        $this->parseDefault($definition, $match);
    }

    /**
     * @param string   $definition
     * @param string[] $match
     */
    private function parseFormat($definition, $match)
    {
        $type = strtolower($match[1]);
        if (!isset(self::$formats[$type])) {
            throw new \SwaggerGen\Exception("Not a string: '{$definition}'");
        }
        $this->format = self::$formats[$type];
    }

    /**
     * @param string   $definition
     * @param string[] $match
     */
    private function parseContent($definition, $match)
    {
        if (strtolower($match[1]) === 'enum') {
            $this->enum = preg_split('/,/', $match[2]);
        } else {
            $this->pattern = empty($match[2]) ? null : $match[2];
        }
    }

    /**
     * @param string   $definition
     * @param string[] $match
     */
    private function parseRange($definition, $match)
    {

        if (!empty($match[3])) {
            if ($match[1] === 'enum') {
                throw new \SwaggerGen\Exception("Range not allowed in enumeration definition: '{$definition}'");
            }
            if ($match[4] === '' && $match[5] === '') {
                throw new \SwaggerGen\Exception("Empty string range: '{$definition}'");
            }
            $exclusiveMinimum = isset($match[3]) ? ($match[3] == '<') : null;
            $this->minLength = $match[4] === '' ? null : $match[4];
            $this->maxLength = $match[5] === '' ? null : $match[5];
            $exclusiveMaximum = isset($match[6]) ? ($match[6] == '>') : null;
            if ($this->minLength !== null && $this->maxLength !== null && $this->minLength > $this->maxLength) {
                self::swap($this->minLength, $this->maxLength);
                self::swap($exclusiveMinimum, $exclusiveMaximum);
            }
            $this->minLength = $this->minLength === null ? null
                : max(0, $exclusiveMinimum ? $this->minLength + 1 : $this->minLength);
            $this->maxLength = $this->maxLength === null ? null
                : max(0, $exclusiveMaximum ? $this->maxLength - 1 : $this->maxLength);
        }
    }

    /**
     * @param string   $definition
     * @param string[] $match
     */
    private function parseDefault($definition, $match)
    {
        $this->default = isset($match[7]) && $match[7] !== '' ? $this->validateDefault($match[7]) : null;
    }

    /**
     * @param string $command The comment command
     * @param string $data    Any data added after the command
     *
     * @return \SwaggerGen\Swagger\Type\AbstractType|boolean
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'default':
                $this->default = $this->validateDefault($data);

                return $this;

            case 'pattern':
                $this->pattern = $data;

                return $this;

            case 'enum':
                if ($this->minLength !== null || $this->maxLength !== null) {
                    throw new \SwaggerGen\Exception("Enumeration not allowed in ranged string: '{$data}'");
                }
                $words = self::wordSplit($data);
                $this->enum = is_array($this->enum) ? array_merge($this->enum, $words) : $words;

                return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge([
            'type'      => 'string',
            'format'    => empty($this->format) ? null : $this->format,
            'pattern'   => $this->pattern,
            'default'   => $this->default,
            'minLength' => $this->minLength ? intval($this->minLength) : null,
            'maxLength' => $this->maxLength ? intval($this->maxLength) : null,
            'enum'      => $this->enum,
        ], parent::toArray()));
    }

    /**
     * Validate a default string value, depending on subtype
     *
     * @param string $value the value to validate
     *
     * @return string the value after validation (may be trimmed and such)
     * @throws \SwaggerGen\Exception
     */
    protected function validateDefault(string $value): string
    {
        if (empty($value)) {
            $type = $this->format ?: ($this->enum ? 'enum' : 'string');
            throw new \SwaggerGen\Exception("Empty {$type} default");
        }

        if (!empty($this->enum) && !in_array($value, $this->enum)) {
            throw new \SwaggerGen\Exception("Invalid enum default: '{$value}'");
        }

        if ($this->maxLength !== null && mb_strlen($value) > $this->maxLength) {
            $type = $this->format ?: ($this->enum ? 'enum' : 'string');
            throw new \SwaggerGen\Exception("Default {$type} length beyond maximum: '{$value}'");
        }

        if ($this->minLength !== null && mb_strlen($value) < $this->minLength) {
            $type = $this->format ?: ($this->enum ? 'enum' : 'string');
            throw new \SwaggerGen\Exception("Default {$type} length beyond minimum: '{$value}'");
        }

        return $value;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
