<?php

class ObjectTypeTest extends PHPUnit_Framework_TestCase
{

	protected $parent;

	protected function setUp()
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\AbstractObject');
	}

	protected function assertPreConditions()
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructNotAnObject()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Not an object: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructNoDefault()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable object definition: 'object=a'");

		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object=a');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Empty object range: 'object[,]'");

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
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable properties definition: 'object(1)'");

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

		$this->setExpectedException('\SwaggerGen\Exception', "Minimum greater than maximum: '6'");

		$object->handleCommand('min', '6');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMinLowerBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Minimum less than zero: '-1'");

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

		$this->setExpectedException('\SwaggerGen\Exception', "Maximum less than minimum: '2'");

		$object->handleCommand('max', '2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandMaxUpperBound()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Maximum less than zero: '-1'");

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

		$this->setExpectedException('\SwaggerGen\Exception', "Missing property definition");

		$object->handleCommand('property', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ObjectType->handleCommand
	 */
	public function testCommandPropertyMissingName()
	{
		$object = new SwaggerGen\Swagger\Type\ObjectType($this->parent, 'object');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ObjectType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing property name: 'string'");

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

}
