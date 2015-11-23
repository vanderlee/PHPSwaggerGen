<?php

class StatementTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Statement::__construct
	 */
	public function testConstructor()
	{
		$object = new \SwaggerGen\Statement('command', 'some data');

		$this->assertInstanceOf('\SwaggerGen\Statement', $object);
		$this->assertSame('command', $object->command);
		$this->assertSame('some data', $object->data);
	}

}
