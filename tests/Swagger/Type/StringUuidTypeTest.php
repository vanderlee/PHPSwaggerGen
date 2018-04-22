<?php

class StringUuidTypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType::__construct
	 */
	public function testConstructNotAUuid()
	{
		$this->expectException('\SwaggerGen\Exception', "Not a uuid: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType::__construct
	 */
	public function testConstructUuid()
	{
		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringUuidType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'format' => 'uuid',
			'pattern' => '^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType::__construct
	 */
	public function testConstructUuidEmptyDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable uuid definition: 'uuid='");

		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid= ');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType::__construct
	 */
	public function testConstructUuidBadDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable uuid definition: 'uuid=123'");

		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid=123');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType::__construct
	 */
	public function testConstructUuidDefault()
	{
		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid=123e4567-e89b-12d3-a456-426655440000');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringUuidType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'format' => 'uuid',
			'pattern' => '^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$',
			'default' => '123e4567-e89b-12d3-a456-426655440000',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringUuidType', $object);

		$this->expectException('\SwaggerGen\Exception', "Empty uuid default");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType->handleCommand
	 */
	public function testCommandDefaultBadValue()
	{
		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringUuidType', $object);

		$this->expectException('\SwaggerGen\Exception', "Invalid uuid default");
		$object->handleCommand('default', 'foobar');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringUuidType->handleCommand
	 */
	public function testCommandDefault()
	{
		$object = new SwaggerGen\Swagger\Type\StringUuidType($this->parent, 'uuid');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringUuidType', $object);

		$object->handleCommand('default', '123e4567-e89b-12d3-a456-426655440000');

		$this->assertSame(array(
			'type' => 'string',
			'format' => 'uuid',
			'pattern' => '^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$',
			'default' => '123e4567-e89b-12d3-a456-426655440000',
				), $object->toArray());
	}

}
