<?php

class ParameterTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_InEmpty()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid in for parameter: ''");

		$object = new \SwaggerGen\Swagger\Parameter($this->parent, '', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_InNotValid()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid in for parameter: 'foo'");

		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'foo', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_DefinitionEmpty()
	{
		$this->expectException('\SwaggerGen\Exception', "No type definition for parameter");

		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_NameEmpty()
	{
		$this->expectException('\SwaggerGen\Exception', "No name for parameter");

		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'int');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_DefinitionUnknownType()
	{
		$this->expectException('\SwaggerGen\Exception', "Type format not recognized: 'foo'");

		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'foo bar');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'int foo');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'path',
			'required' => true,
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_PathAlwaysRequired()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'int foo', false);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'path',
			'required' => true,
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_Optional()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'query', 'int foo', false);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'query',
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_Description()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'int foo Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'path',
			'description' => 'Some words',
			'required' => true,
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_Form()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'form', 'int foo', false);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'formData',
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Parameter::__construct
	 */
	public function testConstructor_Header()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'header', 'int foo', false);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'header',
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Parameter->handleCommand
	 */
	public function testHandleCommand_Passing()
	{
		$object = new \SwaggerGen\Swagger\Parameter($this->parent, 'path', 'int foo', false);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $object);

		$object->handleCommand('default', '123');

		$this->assertSame(array(
			'name' => 'foo',
			'in' => 'path',
			'required' => true,
			'type' => 'integer',
			'format' => 'int32',
			'default' => 123,
				), $object->toArray());
	}

}
