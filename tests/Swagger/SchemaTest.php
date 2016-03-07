<?php

class SchemaTest extends PHPUnit_Framework_TestCase
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
	 * @covers \SwaggerGen\Swagger\Schema::__construct
	 */
	public function testConstructorEmpty()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$this->assertSame(array(
			'type' => 'object',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Schema::__construct
	 */
	public function testConstructorType()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent, 'int');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Schema::__construct
	 */
	public function testConstructorReference()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent, 'User');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$this->assertSame(array(
			'type' => 'object',
			'$ref' => '#/definitions/User',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Schema::__construct
	 */
	public function testConstructorEmptyDescription()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent, 'int', '');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Schema::__construct
	 */
	public function testConstructorDescription()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent, 'int', 'Some more words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'description' => 'Some more words',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Schema->handleCommand
	 */
	public function testCommandPassing()
	{
		$object = new \SwaggerGen\Swagger\Schema($this->parent, 'int');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $object);

		$object->handleCommand('default', '123');

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 123,
				), $object->toArray());
	}

}
