<?php

namespace Test\Parser\Php\ParserTest;

/**
 * @rest\error 400
 */
function testParse_SeeFunction_Error()
{

}

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeFunction
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\see testParse_SeeFunction_Error
	 */
	public function Dummy()
	{
		
	}

}
