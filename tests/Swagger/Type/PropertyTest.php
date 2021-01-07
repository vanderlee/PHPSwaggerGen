<?php

class PropertyTest extends SwaggerGen_TestCase
{

	protected $parent;

	protected function setUp(): void
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\Swagger');
	}

	protected function assertPreConditions(): void
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructEmpty()
	{
		$this->expectException('\SwaggerGen\Exception', "Not a property: ''");

		new SwaggerGen\Swagger\Type\Property($this->parent, '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructReference()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'Address');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'$ref' => '#/definitions/Address',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructReferences()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'array(Address)');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'$ref' => '#/definitions/Address',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructString()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'string');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'type' => 'string',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructDescription()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'string', 'Some words here');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'type' => 'string',
			'description' => 'Some words here',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructReadOnly()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'string', 'Some words here', true);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'type' => 'string',
			'description' => 'Some words here',
			'readOnly' => true
		), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\PropertyType::__construct
	 */
	public function testConstructComplex()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'int[3,10>=6');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 6,
			'minimum' => 3,
			'maximum' => 10,
			'exclusiveMaximum' => true,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandPassing()
	{
		$object = new SwaggerGen\Swagger\Type\Property($this->parent, 'string');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Property', $object);

		$object->handleCommand('default', 'good');
		$this->assertSame(array(
			'type' => 'string',
			'default' => 'good',
				), $object->toArray());
	}

}
