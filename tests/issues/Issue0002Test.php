<?php

class Issue0002Test extends PHPUnit_Framework_TestCase
{

	/**
	 * Tests issue 2; @rest/require should create object on each item
	 * https://github.com/vanderlee/PHPSwaggerGen/issues/2
	 */
	public function testRequireMustBeObject()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			security api_key apikey X-Api-Authentication header
			require api_key
			api Test
			endpoint /test
			method GET something
			require api_key
			response 202
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something","responses":{"202":{"description":"Accepted"}}'
				. ',"security":[{"api_key":[]}]}}},"securityDefinitions":{"api_key":{"type":"apiKey","name":"X-Api-Authentication","in":"header"}}'
				. ',"security":[{"api_key":[]}],"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	public function testRequireAsObjectWithScopes()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			security oauth oauth2 implicit http://www.test
			require oauth user:name
			api Test
			endpoint /test
			method GET something
			require oauth user:name
			response 202
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something","responses":{"202":{"description":"Accepted"}}'
				. ',"security":[{"oauth":["user:name"]}]}}},"securityDefinitions":{"oauth":{"type":"oauth2","flow":"implicit","authorizationUrl":"http:\/\/www.test"}}'
				. ',"security":[{"oauth":["user:name"]}],"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

}
