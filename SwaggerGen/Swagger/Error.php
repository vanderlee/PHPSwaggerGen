<?php

namespace SwaggerGen\Swagger;

/**
 * Specific type of Swagger Response object that helps make addressing error
 * codes easier to specify.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Error extends Response
{

	public function __construct(AbstractObject $parent, $code, $description = null)
	{
		parent::__construct($parent, $code, null, $description);
	}

}
