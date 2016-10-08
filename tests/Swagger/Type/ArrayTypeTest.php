<?php

class ArrayTypeTest extends PHPUnit_Framework_TestCase
{

	protected $parent;

	protected function setUp()
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\Swagger');
	}

	protected function assertPreConditions()
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructNotAArray()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Not an array: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructInvalidDefault()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable array definition: 'array=null'");

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array=null');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructMultiLimit()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Multi array only allowed on query or form parameter: 'multi'");

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'multi');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructMultiOnFormParam()
	{
		$param = new \SwaggerGen\Swagger\Parameter($this->parent, 'form', 'multi foo');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $param);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructEmptyRange()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Empty array range: 'array[,]'");

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[,]');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructRangeStart()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[0,]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
			'minItems' => 0,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructRangeBoth()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array<2,4]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
			'minItems' => 3,
			'maxItems' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructEmptyItems()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array()');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructBadItems()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable items definition: '1'");

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array(1)');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructTypeItems()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array(string)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'type' => 'string',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructTypeItemsPipes()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'pipes(string)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'type' => 'string',
			),
			'collectionFormat' => 'pipes',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType::__construct
	 */
	public function testConstructReferenceItems()
	{
		$this->parent->handleCommand('model', 'Item');

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array(Item)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'$ref' => '#/definitions/Item',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMinUpperBound()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Minimum greater than maximum: '6'");

		$object->handleCommand('min', '6');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMinLowerBound()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Minimum less than zero: '-1'");

		$object->handleCommand('min', '-1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMin()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$object->handleCommand('min', '4');

		$this->assertSame(array(
			'type' => 'array',
			'minItems' => 4,
			'maxItems' => 5,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMaxLowerBound()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Maximum less than minimum: '2'");

		$object->handleCommand('max', '2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMaxUpperBound()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Maximum less than zero: '-1'");

		$object->handleCommand('max', '-1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandMax()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$object->handleCommand('max', '4');

		$this->assertSame(array(
			'type' => 'array',
			'minItems' => 3,
			'maxItems' => 4,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandEmptyItems()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Empty items definition: ''");

		$object->handleCommand('items', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandBadItems()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array[3,5]');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Unparseable items definition: '1'");

		$object->handleCommand('items', '1');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandTypeItems()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$object->handleCommand('items', 'string');

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'type' => 'string',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandReferenceItems()
	{
		$this->parent->handleCommand('model', 'Item');

		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$object->handleCommand('items', 'Item');

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'$ref' => '#/definitions/Item',
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ArrayType->handleCommand
	 */
	public function testCommandPassing()
	{
		$object = new SwaggerGen\Swagger\Type\ArrayType($this->parent, 'array(string)');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ArrayType', $object);

		$object->handleCommand('default', 'good');

		$this->assertSame(array(
			'type' => 'array',
			'items' => array(
				'type' => 'string',
				'default' => 'good',
			),
				), $object->toArray());
	}

}
