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

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0},"paths":{"\/":{"get":{"responses":{"202":{"description":"Accepted"}}}}}}', $output);
	}

	/**
	 *  @covers \SwaggerGen\SwaggerGen::getSwagger
	 */
	public function testGetSwagger_JSON_Pretty()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$this->assertInstanceof('\SwaggerGen\SwaggerGen', $object);

		$output = $object->getSwagger(array('
			endpoint
			method GET
			response 202
		'), array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);

		$this->assertSame('{
    "swagger": 2,
    "info": {
        "title": "undefined",
        "version": 0
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
  version: 0
paths:
  /:
    get:
      responses:
        202:
          description: Accepted
...
', $output);
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
