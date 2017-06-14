<?php

class StringTypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructNotAString()
	{
		$this->expectException('\SwaggerGen\Exception', "Not a string: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->expectException('\SwaggerGen\Exception', "Empty string range: 'string[,]=1'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[,]=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructDefaultTooLongInclusive()
	{
		$this->expectException('\SwaggerGen\Exception', "Default string length beyond maximum: 'long'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[,3]=long');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructDefaultTooLongExclusive()
	{
		$this->expectException('\SwaggerGen\Exception', "Default string length beyond maximum: 'long'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[,4>=long');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructDefaultTooShortInclusive()
	{
		$this->expectException('\SwaggerGen\Exception', "Default string length beyond minimum: 'short'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[6,]=short');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructDefaultTooShortExclusive()
	{
		$this->expectException('\SwaggerGen\Exception', "Default string length beyond minimum: 'short'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string<5,]=short');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructString()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructStringEmptyDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable string definition: 'string='");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string= ');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructStringDefaultLengthInclusive()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[4,4]=word');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'default' => 'word',
			'minLength' => 4,
			'maxLength' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructStringDefaultLengthExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string<3,5>=word');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'default' => 'word',
			'minLength' => 4,
			'maxLength' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructBinary()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'binary');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'format' => 'binary',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructEnumRange()
	{
		$this->expectException('\SwaggerGen\Exception', "Range not allowed in enumeration definition: 'enum(a,b)[,]'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'enum(a,b)[,]');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructEnumInvalidDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid enum default: 'c'");

		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'enum(a,b)=c');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructEnum()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'enum(a,b)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'enum' => array('a', 'b'),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructEnumWithDefault()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'enum(a,b)=a');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'default' => 'a',
			'enum' => array('a', 'b'),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType::__construct
	 */
	public function testConstructPattern()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string([a-z])');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => '[a-z]',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->expectException('\SwaggerGen\Exception', "Empty string default");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
	 */
	public function testCommandDefault()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('default', 'word');

		$this->assertSame(array(
			'type' => 'string',
			'default' => 'word',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
	 */
	public function testCommandPattern()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('pattern', '[a-z]');

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => '[a-z]',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
	 */
	public function testCommandEnumWhenRange()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string[0,]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$this->expectException('\SwaggerGen\Exception', "Enumeration not allowed in ranged string: 'red green blue'");

		$object->handleCommand('enum', 'red green blue');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
	 */
	public function testCommandEnum()
	{
		$object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

		$object->handleCommand('enum', 'red green blue');

		$this->assertSame(array(
			'type' => 'string',
			'enum' => array('red', 'green', 'blue'),
				), $object->toArray());
	}

}
