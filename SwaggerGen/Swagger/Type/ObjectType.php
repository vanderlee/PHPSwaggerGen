<?php

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Exception;

/**
 * Basic object type definition.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ObjectType extends AbstractType
{

    /** @noinspection PhpRegExpUnsupportedModifierInspection */
    private const REGEX_OBJECT_CONTENT = '(?:(\{)(.*)\})?';
    public const REGEX_PROP_START = '/^';
    private const REGEX_PROP_NAME = '([^?!:]+)';
    /** @noinspection PhpRegExpUnsupportedModifierInspection */
    private const REGEX_PROP_REQUIRED = '([\?!])?';
    private const REGEX_PROP_ASSIGN = ':';
    private const REGEX_PROP_DEFINITION = '(.+)';
    /** @noinspection PhpRegExpUnsupportedModifierInspection
     * @noinspection PhpRegExpInvalidDelimiterInspection
     */
    private const REGEX_PROP_ADDITIONAL = '\.\.\.(!|.+)?';
    public const REGEX_PROP_END = '$/';

    private $minProperties;
    private $maxProperties;
    private $discriminator;
    private $additionalProperties;
    private $required = [];

    /**
     * @var Property[]
     */
    private $properties = [];

    /**
     * @var Property
     */
    private $mostRecentProperty;

    /**
     * @param string $command The comment command
     * @param string $data Any data added after the command
     * @return AbstractType|boolean
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            // type name description...
            case 'additionalproperties':
                $value = self::wordShift($data);
                if (is_bool($value)) {
                    $type = $value;
                } else if ($value === 'false') {
                    $type = false;
                } else if ($value === 'true') {
                    $type = true;
                } else {
                    $type = self::typeFactory($this, $value, "Unparseable additional properties definition: '%s'");
                }
                $this->setAdditionalProperties($type);
                return $this;
            case 'discriminator':
                $discriminator = self::wordShift($data);
                $this->setDiscriminator($discriminator);
                return $this;
            case 'property':
            case 'property?':
            case 'property!':
                $definition = self::wordShift($data);
                if (empty($definition)) {
                    throw new Exception("Missing property definition");
                }

                $name = self::wordShift($data);
                if (empty($name)) {
                    throw new Exception("Missing property name: '{$definition}'");
                }

                $readOnly = null;
                $required = false;
                $propertySuffix = substr($command, -1);
                if ($propertySuffix === '!') {
                    $readOnly = true;
                } else if ($propertySuffix !== '?') {
                    $required = true;
                }

                if (($name === $this->discriminator) && !$required) {
                    throw new Exception("Discriminator must be a required property, "
                        . "property '{$name}' is not required");
                }

                unset($this->required[$name]);
                if ($required) {
                    $this->required[$name] = true;
                }

                $this->mostRecentProperty = new Property($this, $definition, $data, $readOnly);
                $this->properties[$name] = $this->mostRecentProperty;
                return $this;

            case 'min':
                $this->minProperties = (int)$data;
                if ($this->minProperties < 0) {
                    throw new Exception("Minimum less than zero: '{$data}'");
                }
                if ($this->maxProperties !== null && $this->minProperties > $this->maxProperties) {
                    throw new Exception("Minimum greater than maximum: '{$data}'");
                }
                $this->minProperties = (int)$data;
                return $this;

            case 'max':
                $this->maxProperties = (int)$data;
                if ($this->minProperties !== null && $this->minProperties > $this->maxProperties) {
                    throw new Exception("Maximum less than minimum: '{$data}'");
                }
                if ($this->maxProperties < 0) {
                    throw new Exception("Maximum less than zero: '{$data}'");
                }
                return $this;
        }

        // Pass through to most recent Property
        if ($this->mostRecentProperty && $this->mostRecentProperty->handleCommand($command, $data)) {
            return $this;
        }

        return parent::handleCommand($command, $data);
    }

    /**
     * @param AbstractType|bool $type
     * @return void
     * @throws Exception
     */
    private function setAdditionalProperties($type): void
    {
        if ($this->additionalProperties !== null) {
            throw new Exception('Additional properties may only be set once');
        }
        $this->additionalProperties = $type;
    }

    /**
     * @param string|false $discriminator
     * @throws Exception
     * @throws Exception
     */
    private function setDiscriminator($discriminator): void
    {
        if (!empty($this->discriminator)) {
            throw new Exception("Discriminator may only be set once, "
                . "trying to change it "
                . "from '{$this->discriminator}' "
                . "to '{$discriminator}'");
        }
        if (isset($this->properties[$discriminator]) && empty($this->required[$discriminator])) {
            throw new Exception("Discriminator must be a required property, "
                . "property '{$discriminator}' is not required");
        }
        $this->discriminator = $discriminator;
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'type' => 'object',
            'required' => array_keys($this->required),
            'properties' => self::objectsToArray($this->properties),
            'minProperties' => $this->minProperties,
            'maxProperties' => $this->maxProperties,
            'discriminator' => $this->discriminator,
            'additionalProperties' => $this->additionalProperties instanceof AbstractType ? $this->additionalProperties->toArray() : $this->additionalProperties,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__;
    }

    /**
     * @throws Exception
     */
    protected function parseDefinition($definition): void
    {
        $definition = self::trim($definition);

        $match = [];
        if (preg_match(self::REGEX_START . self::REGEX_FORMAT . self::REGEX_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) === 1) {
            $match[1] = strtolower($match[1]);
        } elseif (preg_match(self::REGEX_START . self::REGEX_OBJECT_CONTENT . self::REGEX_RANGE . self::REGEX_END, $definition, $match) === 1) {
            $match[1] = 'object';
        } else {
            throw new Exception('Unparseable object definition: \'' . $definition . '\'');
        }

        $this->parseFormat($definition, $match);
        $this->parseProperties($definition, $match);
        $this->parseRange($definition, $match);
    }

    /**
     * @param string $definition
     * @param string[] $match
     * @throws Exception
     */
    private function parseFormat($definition, $match): void
    {
        if (strtolower($match[1]) !== 'object') {
            throw new Exception("Not an object: '{$definition}'");
        }
    }

    /**
     * @param string $definition
     * @param string[] $match
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    private function parseProperties($definition, $match): void
    {
        if (empty($match[2])) {
            return;
        }

        while (($property = self::parseListItem($match[2])) !== '') {
            $prop_match = [];
            if (preg_match(self::REGEX_PROP_START . self::REGEX_PROP_ADDITIONAL . self::REGEX_PROP_END, $property, $prop_match) === 1) {
                if (empty($prop_match[1])) {
                    $this->setAdditionalProperties(true);
                } else if ($prop_match[1] === '!') {
                    $this->setAdditionalProperties(false);
                } else {
                    $this->setAdditionalProperties(self::typeFactory($this, $prop_match[1], "Unparseable additional properties definition: '...%s'"));
                }
                continue;
            }
            if (preg_match(self::REGEX_PROP_START . self::REGEX_PROP_NAME . self::REGEX_PROP_REQUIRED . self::REGEX_PROP_ASSIGN . self::REGEX_PROP_DEFINITION . self::REGEX_PROP_END, $property, $prop_match) !== 1) {
                throw new Exception("Unparseable property definition: '{$property}'");
            }
            $this->properties[$prop_match[1]] = new Property($this, $prop_match[3]);
            if ($prop_match[2] !== '!' && $prop_match[2] !== '?') {
                $this->required[$prop_match[1]] = true;
            }
        }

    }

    /**
     * @param string $definition
     * @param string[] $match
     * @throws Exception
     */
    private function parseRange($definition, $match): void
    {
        if (!empty($match[3])) {
            if ($match[4] === '' && $match[5] === '') {
                throw new Exception("Empty object range: '{$definition}'");
            }

            $exclusiveMinimum = $match[3] === '<';
            $this->minProperties = $match[4] === '' ? null : (int)$match[4];
            $this->maxProperties = $match[5] === '' ? null : (int)$match[5];
            $exclusiveMaximum = isset($match[6]) ? ($match[6] === '>') : null;
            if ($this->minProperties && $this->maxProperties && $this->minProperties > $this->maxProperties) {
                self::swap($this->minProperties, $this->maxProperties);
                self::swap($exclusiveMinimum, $exclusiveMaximum);
            }
            $this->minProperties = $this->minProperties === null ? null : max(0, $exclusiveMinimum ? $this->minProperties + 1 : $this->minProperties);
            $this->maxProperties = $this->maxProperties === null ? null : max(0, $exclusiveMaximum ? $this->maxProperties - 1 : $this->maxProperties);
        }
    }

}
