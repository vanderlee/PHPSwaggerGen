<?php

/**
 * Tests issue 12; @rest\response 200 object(modifications:array(ModificationHistory),users:array(User))
 * https://github.com/vanderlee/PHPSwaggerGen/issues/12
 */
class Issue0012Test extends PHPUnit\Framework\TestCase
{

	public function testIssue()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			api Test
			endpoint /test
			method GET something
			response 200 object(modifications:array(ModificationHistory),users:array(User))
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something"'
				. ',"responses":{"200":{"description":"OK","schema":{"type":"object","required":["modifications","users"]'
				. ',"properties":{"modifications":{"type":"array","items":{"$ref":"#\/definitions\/ModificationHistory"}}'
				. ',"users":{"type":"array","items":{"$ref":"#\/definitions\/User"}}}}}}}}}'
				. ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}
	
}