<?php

namespace SwaggerGen\Swagger\Type;

use SwaggerGen\Swagger\AbstractObject;

/**
 * RFC 4122 UUID type definition
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class StringUuidType extends AbstractRegexType
{

    /**
     * Construct and set up the regular expression for this type
     *
     * @param AbstractObject $parent
     * @param string $definition
     */
    public function __construct(AbstractObject $parent, $definition)
    {
        parent::__construct($parent, $definition, 'uuid', '[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}');
    }

}
