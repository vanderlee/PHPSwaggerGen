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

	public function Dummy()
	{
		/**
	* @rest\endpoint /v1/users/{id}
	* @rest\method GET Return a JSON with all the user attributes
	* @rest\path Int id The ID of the User
	* @rest\response 200 User
		 */
		$app->get('/v1/users/{id:[0-9]+}', function ($request, $response, $args) {
			// ...
		});
	}

}
