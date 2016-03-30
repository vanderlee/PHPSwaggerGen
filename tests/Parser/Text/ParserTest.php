<?php

class Parser_Text_ParserTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::__construct
	 */
	public function testConstructor_Dirs()
	{
		$this->markTestIncomplete('Not yet implemented.');
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::addDirs
	 */
	public function testAddDirs()
	{
		$this->markTestIncomplete('Not yet implemented.');
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText('title Some words');

		$this->assertCount(1, $statements);
		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Whitespace()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText(" \t title   \t\t Some words \t ");

		$this->assertCount(1, $statements);
		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Multiple_LF()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText("title Some words\nSome Random words");

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Multiple_CR()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText("title Some words\rSome Random words");

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Multiple_CRLF()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText("title Some words\r\nSome Random words");

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Multiple_BlankLines()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText("title Some words\r\n\n\n\rSome Random words");

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parseText
	 */
	public function testParseText_Dirs()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parseText("title Some words\r\n\n\n\rSome Random words", array(
			__DIR__ . '/ParserTest/not used by text parser',
		));

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Parser::parse
	 */
	public function testParse()
	{
		$object = new \SwaggerGen\Parser\Text\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse.txt', array(
			__DIR__ . '/ParserTest/not used by text parser',
		));

		$this->assertCount(2, $statements);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[0]);
		$this->assertSame('title', $statements[0]->command);
		$this->assertSame('Some words', $statements[0]->data);

		$this->assertInstanceOf('\SwaggerGen\Statement', $statements[1]);
		$this->assertSame('Some', $statements[1]->command);
		$this->assertSame('Random words', $statements[1]->data);
	}

}
