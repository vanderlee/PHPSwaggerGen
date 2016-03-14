<?php

class Parser_Php_StatementTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Parser\Php\Statement::__construct
	 */
	public function testConstructor()
	{
		$object = new \SwaggerGen\Parser\Php\Statement('command', 'some data', 'file', 123);
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Statement', $object);

		$this->assertSame('command', $object->command);
		$this->assertSame('some data', $object->data);
		$this->assertSame('file', $object->file);
		$this->assertSame(123, $object->line);
	}

}
