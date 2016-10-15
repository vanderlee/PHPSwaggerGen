<?php

class SwaggerGenTest extends PHPUnit_Framework_TestCase
{

	/**
	 *  @covers \SwaggerGen\SwaggerGen::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$this->setExpectedException('\SwaggerGen\Exception', 'No path defined');
		$object->getSwagger(array());
	}
	
	/**
	 *  @covers \SwaggerGen\SwaggerGen::getSwagger
	 */
	public function testGetSwagger_ShortestPossible()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$array = $object->getSwagger(array('
			endpoint
			method GET
			response 202
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0},"paths":{"\/":{"get":{"responses":{"202":{"description":"Accepted"}}}}}}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::__construct
	 */
	public function testConstructor_BadContext()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$this->setExpectedException('\SwaggerGen\StatementException', 'Invalid error code: \'\'');
		try {
			$object->getSwagger(array('
				endpoint
				method GET
				error
			'));
		} catch (\SwaggerGen\StatementException $e) {
			$this->assertSame("Invalid error code: ''", $e->getMessage());
			$this->assertSame(3, $e->getStatement()->getLine());
			
			throw $e; // rethrow to satisfy expected exception check
		}
	}
	
	/**
	 *  @covers \SwaggerGen\SwaggerGen::getSwagger
	 */
	public function testGetSwagger_JSON()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$output = $object->getSwagger(array('
			endpoint
			method GET
			response 202
		'), array(), \SwaggerGen\SwaggerGen::FORMAT_JSON);

		$this->assertSame('{"swagger":"2.0","info":{"title":"undefined","version":"0"},"paths":{"\/":{"get":{"responses":{"202":{"description":"Accepted"}}}}}}', $output);
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::getSwagger
	 */
	public function testGetSwagger_JSON_Pretty()
	{
		if (!defined('JSON_PRETTY_PRINT')) {
			$this->markTestSkipped('JSON_PRETTY_PRINT available since PHP 5.4.0');
		}

		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$output = $object->getSwagger(array('
			endpoint
			method GET
			response 202
		'), array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);

		$this->assertSame('{
    "swagger": "2.0",
    "info": {
        "title": "undefined",
        "version": "0"
    },
    "paths": {
        "\/": {
            "get": {
                "responses": {
                    "202": {
                        "description": "Accepted"
                    }
                }
            }
        }
    }
}', $output);
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::getSwagger
	 */
	public function testGetSwagger_YAML()
	{
		if (!extension_loaded('yaml')) {
			$this->markTestSkipped('The YAML extension is not available.');
		} else {
			$object = new \SwaggerGen\SwaggerGen();
			$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

			$output = $object->getSwagger(array('
				endpoint
				method GET
				response 202
			'), array(), \SwaggerGen\SwaggerGen::FORMAT_YAML);

			$this->assertSame('---
swagger: "2.0"
info:
  title: undefined
  version: "0"
paths:
  /:
    get:
      responses:
        202:
          description: Accepted
...
', $output);
		}
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::define
	 */
	public function testDefine_NotDefined()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$array = $object->getSwagger(array('
			endpoint
			method GET
			if d
			response 202
			else
			response 204
			endif
		'));
		$this->assertArrayNotHasKey(202, $array['paths']['/']['get']['responses']);
		$this->assertArrayHasKey(204, $array['paths']['/']['get']['responses']);
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::define
	 */
	public function testDefine_Defined()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$object->define('d');
		$array = $object->getSwagger(array('
			endpoint
			method GET
			if d
			response 202
			else
			response 204
			endif
		'));
		$this->assertArrayHasKey(202, $array['paths']['/']['get']['responses']);
		$this->assertArrayNotHasKey(204, $array['paths']['/']['get']['responses']);
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::define
	 */
	public function testUndefine()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$object->define('d');
		$object->undefine('d');
		$array = $object->getSwagger(array('
			endpoint
			method GET
			if d
			response 202
			else
			response 204
			endif
		'));
		$this->assertArrayNotHasKey(202, $array['paths']['/']['get']['responses']);
		$this->assertArrayHasKey(204, $array['paths']['/']['get']['responses']);
	}
}
