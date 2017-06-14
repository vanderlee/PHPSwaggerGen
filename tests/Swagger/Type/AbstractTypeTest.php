<?php

class AbstractTypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\AbstractType::__construct
	 */
	public function testConstruct()
	{
		$stub = $this->getMockForAbstractClass('SwaggerGen\Swagger\Type\AbstractType', array(
			$this->parent,
			'whatever'
		));

		$this->assertFalse($stub->handleCommand('x-extra', 'whatever'));
	}

}
