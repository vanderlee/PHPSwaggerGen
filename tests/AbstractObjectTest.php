<?php

class AbstractObjectTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Swagger\AbstractObject::words_shift
	 */
	public function testWords_shift()
	{
		$text = 'quite a few words';

		$this->assertSame('quite', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('a few words', $text);

		$this->assertSame('a', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('few words', $text);

		$this->assertSame('few', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('words', $text);

		$this->assertSame('words', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('', $text);

		$this->assertSame(false, \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('', $text);
	}

	/**
	 * @covers \SwaggerGen\Swagger\AbstractObject::words_shift
	 */
	public function testWords_shift_whitespace()
	{
		$text = "    quite  a\nfew   \r  \n\r words \t";

		$this->assertSame('quite', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('a', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('few', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('words', \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame(false, \SwaggerGen\Swagger\AbstractObject::words_shift($text));
		$this->assertSame('', $text);
	}

	/**
	 * @covers \SwaggerGen\Swagger\AbstractObject::words_split
	 */
	public function testWords_split()
	{
		$text = "    quite  a\nfew   \r  \n\r words \t";

		$this->assertSame(array('quite', 'a', 'few', 'words'), \SwaggerGen\Swagger\AbstractObject::words_split($text));
		$this->assertSame("    quite  a\nfew   \r  \n\r words \t", $text);
	}

}
