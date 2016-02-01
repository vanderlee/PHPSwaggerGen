<?php

class IntegerTypeTest extends PHPUnit_Framework_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructNotAInteger()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Not an integer: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructInvalidDefault()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable integer definition: 'integer=null'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer=null');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructNoSpecificationAllowed()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable integer definition: 'integer()=1'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer()=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Empty integer range: 'integer[,]=1'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[,]=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructInteger()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructLong()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'long');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int64',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructPositive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer=1');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 1,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructNegative()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer=-1');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => -1,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructRangeInclusive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[2,5]=3');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 3,
			'minimum' => 2,
			'maximum' => 5,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructRangeExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer<2,5>=3');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 3,
			'minimum' => 2,
			'exclusiveMinimum' => true,
			'maximum' => 5,
			'exclusiveMaximum' => true,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructDefaultBeyondMaximumExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer<2,5>=4');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default integer beyond maximum: '5'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer<2,5>=5');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructDefaultBeyondMaximum()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[2,5]=5');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default integer beyond maximum: '6'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[2,5]=6');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructDefaultBeyondMinimumExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer<2,5>=3');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default integer beyond minimum: '2'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer<2,5>=2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType::__construct
	 */
	public function testConstructDefaultBeyondMinimum()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[2,5]=2');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default integer beyond minimum: '1'");

		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[2,5]=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid integer default: ''");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandDefaultInvalidValue()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid integer default: 'foo'");
		$object->handleCommand('default', 'foo');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandDefaultPositive()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$object->handleCommand('default', '123');

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => 123,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandDefaultNegative()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$object->handleCommand('default', '-123');

		$this->assertSame(array(
			'type' => 'integer',
			'format' => 'int32',
			'default' => -123,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandDefaultBeyondRange()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer[-100,100]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default integer beyond minimum: '-123'");
		$object->handleCommand('default', '-123');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandEnum()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$object->handleCommand('enum', '-1 2 123');
		$object->handleCommand('enum', '-123 0');

		$this->assertEquals(array(
			'type' => 'integer',
			'format' => 'int32',
			'enum' => array(
				-1,
				2,
				123,
				-123,
				0,
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\IntegerType->handleCommand
	 */
	public function testCommandStep()
	{
		$object = new SwaggerGen\Swagger\Type\IntegerType($this->parent, 'integer');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\IntegerType', $object);

		$object->handleCommand('step', '3');

		$this->assertEquals(array(
			'type' => 'integer',
			'format' => 'int32',
			'multipleOf' => 3,
				), $object->toArray());
	}

}
