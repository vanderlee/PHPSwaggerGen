<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeInheritedThisMethod extends testParse_SeeInheritedThisMethod_Super
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\see $this->Method
	 */
	public function Dummy()
	{
		
	}

}

class testParse_SeeInheritedThisMethod_Super
{

	/**
	 * @rest\error 400
	 */
	private function Method()
	{

	}

}
