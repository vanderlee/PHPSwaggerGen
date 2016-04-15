<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeObjectMethod
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\see testParse_SeeObjectMethod_Other->Method
	 */
	public function Dummy()
	{
		
	}

}

class testParse_SeeObjectMethod_Other
{

	/**
	 * @rest\error 400
	 */
	private function Method()
	{

	}

}
