<?php

class AbstractTypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\AbstractType::__construct
	 */
	public function testConstruct()
	{
		$stub = $this->getMockForAbstractClass('SwaggerGen\Swagger\Type\AbstractType', array(
			$this->parent,
			'whatever'
		));

		$this->assertFalse($stub->handleCommand('x-extra', 'whatever'));
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
	 */
	public function testCommand_Example()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('example', 'foo');

		$this->assertSame(array(
			'type' => 'string',
			'example' => 'foo',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
	 */
	public function testCommand_Example_Json()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('example', '{foo:bar}');

		$this->assertSame(array(
			'type' => 'string',
			'example' => array(
				'foo' => 'bar',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
	 */
	public function testCommand_Example_Json_Multiple_Properties()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('example', '{foo:bar,baz:bat}');

		$this->assertSame(array(
			'type' => 'string',
			'example' => array(
				'foo' => 'bar',
				'baz' => 'bat',
			),
				), $object->toArray());
	}
}
