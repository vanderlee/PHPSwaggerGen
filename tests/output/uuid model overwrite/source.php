<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\title UUID
 * @rest\model uuid
 * @rest\property string ip
 * @rest\property string path
 * @rest\api MyApi Example
 */
class Example
{

	/**
	 * @rest\endpoint /endpoint
	 * @rest\method GET Something
	 * @rest\response 200 uuid
	 */
	public function Dummy()
	{
		
	}

}
