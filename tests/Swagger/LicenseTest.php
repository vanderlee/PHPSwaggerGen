<?php

class LicenseTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\License::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor2Unknown(): void
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'Name');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$this->assertSame(array(
			'name' => 'Name',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\License::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor2Known()
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'MIT');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$this->assertSame(array(
			'name' => 'MIT',
			'url' => 'http://opensource.org/licenses/MIT',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\License::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor3Unknown()
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'Name', 'http://example');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$this->assertSame(array(
			'name' => 'Name',
			'url' => 'http://example',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\License::__construct
	 * @covers \SwaggerGen\Swagger\License::toArray
	 */
	public function testConstructor3Known()
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'MIT', 'http://example');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$this->assertSame(array(
			'name' => 'MIT',
			'url' => 'http://example',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::handleCommand
	 */
	public function testCommandName()
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'MIT');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$object->handleCommand('name', 'GPL-3');

		$this->assertSame(array(
			'name' => 'GPL-3',
			'url' => 'http://opensource.org/licenses/MIT',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Tag::handleCommand
	 */
	public function testCommandUrl()
	{
		$object = new \SwaggerGen\Swagger\License($this->parent, 'MIT');

		$this->assertInstanceOf('\SwaggerGen\Swagger\License', $object);

		$object->handleCommand('url', 'http://example');

		$this->assertSame(array(
			'name' => 'MIT',
			'url' => 'http://example',
				), $object->toArray());
	}

}
