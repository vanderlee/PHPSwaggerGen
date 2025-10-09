<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class Issue0015Test extends TestCase
{

    /**
     * Tests issue 15; Allow enumeration of property values
     * https://github.com/vanderlee/PHPSwaggerGen/issues/15
     */
    public function testPropertyEnumeration()
    {
        $object = new \SwaggerGen\SwaggerGen();
        $array = $object->getSwagger(array('
			api Test
			definition ClassName
			title ClassName
			property string type
			enum ClassName
			property int meaningOfLifeTheUniverseAndEverything
			enum 42
			endpoint /test
			method GET something
			response 204			
		'));

        $this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
            . ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something","responses":{"204":{"description":"No Content"}}}}}'
            . ',"definitions":{"ClassName":{"type":"object","required":["type","meaningOfLifeTheUniverseAndEverything"],"properties":{"type":{"type":"string","enum":["ClassName"]},"meaningOfLifeTheUniverseAndEverything":{"type":"integer","format":"int32","enum":[42]}},"title":"ClassName"}}'
            . ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
    }

}
