<?php

class TagTest extends PHPUnit_Framework_TestCase
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
	 * @covers \SwaggerGen\Swagger\Tag::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor2()
	{
		$object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

		$this->assertSame(array(
			'name' => 'Name',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor3()
	{
		$object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name', 'Description');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

		$this->assertSame(array(
			'name' => 'Name',
			'description' => 'Description',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::getName
	 */
	public function testGetName()
	{
		$object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

		$this->assertSame('Name', $object->getName());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::handleCommand
	 */
	public function testCommandDescription()
	{
		$object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

		$object->handleCommand('description', 'Command Description');

		$this->assertSame(array(
			'name' => 'Name',
			'description' => 'Command Description',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::handleCommand
	 */
	public function testCommandDescriptionOverwrite()
	{
		$object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name', 'Description');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

		$object->handleCommand('description', 'Command Description');

		$this->assertSame(array(
			'name' => 'Name',
			'description' => 'Command Description',
				), $object->toArray());
	}

}
