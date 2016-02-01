<?php

class NumberTypeTest extends PHPUnit_Framework_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructNotANumber()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Not a number: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructInvalidDefault()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable number definition: 'float=null'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float=null');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructNoSpecificationAllowed()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable number definition: 'float()=1'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float()=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Empty number range: 'float[,]=1'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[,]=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructNumber()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructLong()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'double');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'double',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructPositive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float=1.23');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => 1.23,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructNegative()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float=-1.23');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => -1.23,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructRangeInclusive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[2,5]=3');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => 3.,
			'minimum' => 2.,
			'maximum' => 5.,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructRangeExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float<2,5>=3');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => 3.,
			'minimum' => 2.,
			'exclusiveMinimum' => true,
			'maximum' => 5.,
			'exclusiveMaximum' => true,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructDefaultBeyondMaximumExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float<2,5>=4');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default number beyond maximum: '5'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float<2,5>=5');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructDefaultBeyondMaximum()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[2,5]=5');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default number beyond maximum: '6'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[2,5]=6');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructDefaultBeyondMinimumExclusive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float<2,5>=3');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default number beyond minimum: '2'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float<2,5>=2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType::__construct
	 */
	public function testConstructDefaultBeyondMinimum()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[2,5]=2');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default number beyond minimum: '1'");

		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[2,5]=1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid number default: ''");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandDefaultInvalidValue()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid number default: 'foo'");
		$object->handleCommand('default', 'foo');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandDefaultPositive()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$object->handleCommand('default', '1.23');

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => 1.23,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandDefaultNegative()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$object->handleCommand('default', '-1.23');

		$this->assertSame(array(
			'type' => 'number',
			'format' => 'float',
			'default' => -1.23,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandDefaultBeyondRange()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float[-1.22,1.22]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Default number beyond minimum: '-1.23'");
		$object->handleCommand('default', '-1.23');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandEnum()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$object->handleCommand('enum', '-1 2 1.23');
		$object->handleCommand('enum', '-1.23 0');

		$this->assertEquals(array(
			'type' => 'number',
			'format' => 'float',
			'enum' => array(
				-1.,
				2.,
				1.23,
				-1.23,
				0.,
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\NumberType->handleCommand
	 */
	public function testCommandStep()
	{
		$object = new SwaggerGen\Swagger\Type\NumberType($this->parent, 'float');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\NumberType', $object);

		$object->handleCommand('step', '3');

		$this->assertEquals(array(
			'type' => 'number',
			'format' => 'float',
			'multipleOf' => 3,
				), $object->toArray());
	}

}
