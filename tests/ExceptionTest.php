<?php

class ExceptionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Exception::__construct
	 */
	public function testConstructor0()
	{
		$this->setExpectedException('\SwaggerGen\Exception');

		throw new \SwaggerGen\Exception('', 0);
	}

	/**
	 * @covers \SwaggerGen\Exception::__construct
	 */
	public function testConstructor1()
	{
		$this->setExpectedException('\SwaggerGen\Exception', 'This is a message');

		throw new \SwaggerGen\Exception('This is a message', 0);
	}

	/**
	 * @covers \SwaggerGen\Exception::__construct
	 */
	public function testConstructor2()
	{
		$this->setExpectedException('\SwaggerGen\Exception', 'This is a message', 1234);

		throw new \SwaggerGen\Exception('This is a message', 1234);
	}

}
