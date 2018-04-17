<?php

class EmailTypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstructNotAnEmail()
	{
		$this->expectException('\SwaggerGen\Exception', "Not an email: 'wrong'");

		new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstruct()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\EmailType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\EmailType::PATTERN,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstructEmptyDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable email definition: 'email='");

		new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email= ');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstructDefaultDoubleAt()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid email default value: 'test@test@test.test'");

		new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email=test@test@test.test');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstructDefaultUntrimmed()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid email default value: ' test@test.test'");

		new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email= test@test.test');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType::__construct
	 */
	public function testConstructDefault()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email=test@test.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\EmailType', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\EmailType::PATTERN,
			'default' => 'test@test.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\EmailType', $object);

		$this->expectException('\SwaggerGen\Exception', "Empty email default");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\EmailType->handleCommand
	 */
	public function testCommandDefault()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\EmailType($this->parent, 'email');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\EmailType', $object);

		$object->handleCommand('default', 'test@test.test');

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\EmailType::PATTERN,
			'default' => 'test@test.test',
				), $object->toArray());
	}

}
