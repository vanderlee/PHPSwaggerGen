<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\title CurlyBraceFunction
 * @rest\api MyApi Example
 */
class testParse_CurlyBraces
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 */
	public function Dummy()
	{
		echo "{$foo}";
	}
	
	/**
	 * @rest\endpoint /endpoint2
	 * @rest\method GET Something2
	 */
	public function Dummy2()
	{
	}

}
