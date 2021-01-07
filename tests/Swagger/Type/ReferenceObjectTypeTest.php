<?php

class ReferenceObjectTypeTest extends SwaggerGen_TestCase
{

	protected $parent;

	protected function setUp(): void
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\Swagger');
	}

	protected function assertPreConditions(): void
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\ReferenceObjectType::__construct
	 */
	public function testConstructBothConsumes()
	{
		$this->parent->handleCommand('model', 'blah');
		
		$object = new SwaggerGen\Swagger\Type\ReferenceObjectType($this->parent, 'blah');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\ReferenceObjectType', $object);

		$this->assertSame(array(
			'$ref' => '#/definitions/blah',
				), $object->toArray());
	}

}
