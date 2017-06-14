<?php

class ErrorTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Error::__construct
	 */
	public function testConstructor_200()
	{
		$object = new \SwaggerGen\Swagger\Error($this->parent, 200);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

		$this->assertSame(array(
			'description' => 'OK',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Error::__construct
	 */
	public function testConstructor_404()
	{
		$object = new \SwaggerGen\Swagger\Error($this->parent, 404);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

		$this->assertSame(array(
			'description' => 'Not Found',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Error::__construct
	 */
	public function testConstructor_Description()
	{
		$object = new \SwaggerGen\Swagger\Error($this->parent, '200', 'Fine And Dandy');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

		$this->assertSame(array(
			'description' => 'Fine And Dandy',
				), $object->toArray());
	}

	/**
	 * Just checks that the `header` command is inherited. Actual tests for
	 * this command are in `ResponseTest`
	 * @covers \SwaggerGen\Swagger\Type\Error->handleCommand
	 */
	public function testHandleCommand_Header()
	{
		$object = new \SwaggerGen\Swagger\Error($this->parent, 200);

		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

		$object->handleCommand('header', 'integer bar');

		$this->assertSame(array(
			'description' => 'OK',
			'headers' => array(
				'bar' => array(
					'type' => 'integer',
				),
			),
				), $object->toArray());
	}

}
