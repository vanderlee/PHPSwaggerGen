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
		$this->assertSame('command', $object->getCommand());
		$this->assertSame('some data', $object->getData());
	}
	/**
	 * @covers \SwaggerGen\Statement::__construct
	 */
	public function testConstructor_File()
	{
		$object = new \SwaggerGen\Statement('command', 'some data', 'file', 123);
		$this->assertInstanceOf('\SwaggerGen\Statement', $object);

		$this->assertSame('command', $object->getCommand());
		$this->assertSame('some data', $object->getData());
		$this->assertSame('file', $object->getFile());
		$this->assertSame(123, $object->getLine());
	}	
}
