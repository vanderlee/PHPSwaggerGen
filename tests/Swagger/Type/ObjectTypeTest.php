<?php

class ObjectTypeTest extends SwaggerGen_TestCase
{

	protected $parent;

	protected function setUp(): void
	{
		$this->parent = new \SwaggerGen\Swagger\Swagger;
	}

	protected function assertPreConditions(): void
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructNotAnObject()
	{
		$this->expectException('\SwaggerGen\Exception', "Not an object: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructNoDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable object definition: 'object=a'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object=a');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->expectException('\SwaggerGen\Exception', "Empty object range: 'object[,]'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[,]');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructRangeStart()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[0,]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->assertSame(array(
			'type' => 'object',
			'minProperties' => 0,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructRangeBoth()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object<2,4]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->assertSame(array(
			'type' => 'object',
			'minProperties' => 3,
			'maxProperties' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructEmptyProperties()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object()');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->assertSame(array(
			'type' => 'object',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructBadProperties()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable property definition: '1'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(1)');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructTypeProperty()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(foo:string)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->assertSame(array(
			'type' => 'object',
			'required' => array(
				'foo',
			),
			'properties' => array(
				'foo' => array(
					'type' => 'string',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructTypeProperties()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(foo:string,bar?:int[3,10>=5)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->assertSame(array(
			'type' => 'object',
			'required' => array(
				'foo',
			),
			'properties' => array(
				'foo' => array(
					'type' => 'string',
				),
				'bar' => array(
					'type' => 'integer',
					'format' => 'int32',
					'default' => 5,
					'minimum' => 3,
					'maximum' => 10,
					'exclusiveMaximum' => true,
				)
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMinUpperBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Minimum greater than maximum: '6'");

		$object->handleCommand('min', '6');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMinLowerBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Minimum less than zero: '-1'");

		$object->handleCommand('min', '-1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMin()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$object->handleCommand('min', '4');

		$this->assertSame(array(
			'type' => 'object',
			'minProperties' => 4,
			'maxProperties' => 5,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMaxLowerBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Maximum less than minimum: '2'");

		$object->handleCommand('max', '2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMaxUpperBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Maximum less than zero: '-1'");

		$object->handleCommand('max', '-1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMax()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$object->handleCommand('max', '4');

		$this->assertSame(array(
			'type' => 'object',
			'minProperties' => 3,
			'maxProperties' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandPropertyMissingDefinition()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Missing property definition");

		$object->handleCommand('property', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandPropertyMissingName()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->expectException('\SwaggerGen\Exception', "Missing property name: 'string'");

		$object->handleCommand('property', 'string');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandProperty()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$object->handleCommand('property', 'string foo Some words here');

		$this->assertSame(array(
			'type' => 'object',
			'required' => array(
				'foo',
			),
			'properties' => array(
				'foo' => array(
					'type' => 'string',
					'description' => 'Some words here',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandPropertyOptional()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$object->handleCommand('property?', 'string foo Some words here');

		$this->assertSame(array(
			'type' => 'object',
			'properties' => array(
				'foo' => array(
					'type' => 'string',
					'description' => 'Some words here',
				),
			),
				), $object->toArray());
	}

	public function testObjectProperties()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			api Test
			endpoint /test
			method GET something
			response 200 object(a:array(A),b:array(B))
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something"'
				. ',"responses":{"200":{"description":"OK","schema":{"type":"object","required":["a","b"]'
				. ',"properties":{"a":{"type":"array","items":{"$ref":"#\/definitions\/A"}}'
				. ',"b":{"type":"array","items":{"$ref":"#\/definitions\/B"}}}}}}}}}'
				. ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	public function testObjectPropertiesReadOnly()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			api Test
			endpoint /test
			method GET something
			response 200 object(a!:array(A),b:array(B))
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
			. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something"'
			. ',"responses":{"200":{"description":"OK","schema":{"type":"object","required":["b"]'
			. ',"properties":{"a":{"type":"array","items":{"$ref":"#\/definitions\/A"}}'
			. ',"b":{"type":"array","items":{"$ref":"#\/definitions\/B"}}}}}}}}}'
			. ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	public function testDeepObjectProperties()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			api Test
			endpoint /test
			method GET something
			response 200 object(a:object(c:csv(C),d:int),b?:array(B))
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something"'
				. ',"responses":{"200":{"description":"OK","schema":{"type":"object"'
				. ',"required":["a"],"properties":{'
				. '"a":{"type":"object","required":["c","d"],"properties":{'
				. '"c":{"type":"array","items":{"$ref":"#\/definitions\/C"}}'
				. ',"d":{"type":"integer","format":"int32"}}}'
				. ',"b":{"type":"array","items":{"$ref":"#\/definitions\/B"}}}}}}}}}'
				. ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	public function testDeepObjectProperties_JsonNotation()
	{
		$object = new \SwaggerGen\SwaggerGen();
		$array = $object->getSwagger(array('
			api Test
			endpoint /test
			method GET something
			response 200 {a:{c:csv(C),d:int},b?:[B]}
		'));

		$this->assertSame('{"swagger":2,"info":{"title":"undefined","version":0}'
				. ',"paths":{"\/test":{"get":{"tags":["Test"],"summary":"something"'
				. ',"responses":{"200":{"description":"OK","schema":{"type":"object"'
				. ',"required":["a"],"properties":{'
				. '"a":{"type":"object","required":["c","d"],"properties":{'
				. '"c":{"type":"array","items":{"$ref":"#\/definitions\/C"}}'
				. ',"d":{"type":"integer","format":"int32"}}}'
				. ',"b":{"type":"array","items":{"$ref":"#\/definitions\/B"}}}}}}}}}'
				. ',"tags":[{"name":"Test"}]}', json_encode($array, JSON_NUMERIC_CHECK));
	}

	public function testAddingOptionalPropertyFailsWhenItIsDiscriminator()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('discriminator', 'type');
		$this->expectException('\SwaggerGen\Exception', "Discriminator must be a required property, property 'type' is not required");
		$object->handleCommand('property?', 'string type');
	}

	public function testAddingReadonlyPropertyFailsWhenItIsDiscriminator()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('discriminator', 'type');
		$this->expectException('\SwaggerGen\Exception', "Discriminator must be a required property, property 'type' is not required");
		$object->handleCommand('property!', 'string type');
	}

	public function testDiscriminatingOnOptionalPropertyFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('property?', 'string type');
		$this->expectException('\SwaggerGen\Exception', "Discriminator must be a required property, property 'type' is not required");
		$object->handleCommand('discriminator', 'type');
	}

	public function testDiscriminatingOnReadonlyPropertyFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('property!', 'string type');
		$this->expectException('\SwaggerGen\Exception', "Discriminator must be a required property, property 'type' is not required");
		$object->handleCommand('discriminator', 'type');
	}

	public function testSettingAnotherDiscriminatorFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('discriminator', 'type');
		$this->expectException('\SwaggerGen\Exception', "Discriminator may only be set once, trying to change it from 'type' to 'petType'");
		$object->handleCommand('discriminator', 'petType');
	}

	public function testSettingAdditionalPropertiesTwiceInlineFails()
	{
		$this->expectException('\SwaggerGen\Exception', "Additional properties may only be set once");
		new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(...,...)');
	}

	public function testSettingAdditionalPropertiesTwiceWithCommandFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$object->handleCommand('additionalproperties', 'false');
		$this->expectException('\SwaggerGen\Exception', "Additional properties may only be set once");
		$object->handleCommand('additionalproperties', 'false');
	}

	public function testSettingAdditionalPropertiesTwiceMixedFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(...)');
		$this->expectException('\SwaggerGen\Exception', "Additional properties may only be set once");
		$object->handleCommand('additionalproperties', 'string');
	}

	public function testSettingAdditionalPropertiesWithInvalidSyntaxFails()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable property definition: '!...!'");
		new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(!...!)');
	}

	public function testSettingAdditionalPropertiesWithInvalidTypeInlineFails()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable additional properties definition: '...?&#'");
		new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object(...?&#)');
	}

	public function testSettingAdditionalPropertiesWithInvalidTypeCommandFails()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');
		$this->expectException('\SwaggerGen\Exception', "Unparseable additional properties definition: '?&#'");
		$object->handleCommand('additionalproperties', '?&#');
	}


}
