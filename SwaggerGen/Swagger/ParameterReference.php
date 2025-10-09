<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a reference to a Parameter.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class ParameterReference extends AbstractObject implements IParameter
{

    private $reference;

    public function __construct(AbstractObject $parent, $reference)
    {
        parent::__construct($parent);

        $this->reference = $reference;
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array(
            '$ref' => '#/parameters/' . $this->reference,
        ));
    }

    public function __toString()
    {
        return __CLASS__ . ' `' . $this->reference . '`';
    }

    public function getName()
    {
        return $this->reference;
    }

}
