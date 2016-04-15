<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_Autoload
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\see testParse_Autoload_Other::Method
	 */
	public function Dummy()
	{
		
	}

}