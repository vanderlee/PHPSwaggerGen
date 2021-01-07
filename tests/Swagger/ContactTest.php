<?php

class ContactTest extends SwaggerGen_TestCase
{

	protected $parent;

	protected function setUp(): void
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\AbstractObject');
	}

	protected function assertPreConditions(): void
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::__construct
	 */
	public function testConstructorEmpty()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$this->assertSame(array(), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::__construct
	 */
	public function testConstructorName()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, 'Name');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$this->assertSame(array(
			'name' => 'Name',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::__construct
	 */
	public function testConstructorUrl()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, null, 'http://example.com');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$this->assertSame(array(
			'url' => 'http://example.com',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::__construct
	 */
	public function testConstructorEmail()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, null, null, 'test@example.com');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$this->assertSame(array(
			'email' => 'test@example.com',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::handleCommand
	 */
	public function testCommandName()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, 'Name', 'http://example.com', 'test@example.com');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$object->handleCommand('name', 'Somebody');

		$this->assertSame(array(
			'name' => 'Somebody',
			'url' => 'http://example.com',
			'email' => 'test@example.com',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::handleCommand
	 */
	public function testCommandUrl()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, 'Name', 'http://example.com', 'test@example.com');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$object->handleCommand('url', 'http://site.test');

		$this->assertSame(array(
			'name' => 'Name',
			'url' => 'http://site.test',
			'email' => 'test@example.com',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Contact::handleCommand
	 */
	public function testCommandEmail()
	{
		$object = new \SwaggerGen\Swagger\Contact($this->parent, 'Name', 'http://example.com', 'test@example.com');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Contact', $object);

		$object->handleCommand('email', 'somebody@somewhere.test');

		$this->assertSame(array(
			'name' => 'Name',
			'url' => 'http://example.com',
			'email' => 'somebody@somewhere.test',
				), $object->toArray());
	}

}
