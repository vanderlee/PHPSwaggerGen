<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\title Minimal
 * @rest\api MyApi Example
 * @rest\response user string[1,16] Username
 */
class Example
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\response user 200
	 */
	public function Dummy()
	{
		
	}

}
