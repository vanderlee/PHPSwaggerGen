<?php

class Parser_Php_ParserTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Test if the statement matches the expected values
	 * @param SwaggerGen\Parser\Php\Statement $statement
	 * @param string $command
	 * @param string $data
	 */
	private function assertStatement(SwaggerGen\Parser\Php\Statement $statement, $command, $data = '')
	{
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Statement', $statement);
		$this->assertSame($command, $statement->command);
		$this->assertSame($data, $statement->data);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::__construct
	 */
	public function testConstructor_Dirs()
	{
		$this->markTestIncomplete('Not yet implemented.');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::addDirs
	 */
	public function testAddDirs()
	{
		$this->markTestIncomplete('Not yet implemented.');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_NoMethods()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_NoMethods.php');

		$this->assertCount(2, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'version', '2');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_WithMethod()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_WithMethod.php');

		$this->assertCount(4, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'version', '2');
		$this->assertStatement($statements[2], 'description', 'some description');
		$this->assertStatement($statements[3], 'method', 'get something');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_LineContinuation()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_LineContinuation.php');

		$this->assertCount(2, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'description', 'About this strange little class');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_InClass()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_InClass.php');

		$this->assertCount(3, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'description', 'Some description');
		$this->assertStatement($statements[2], 'license', 'mit');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_CommentsTypes()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_CommentsTypes.php');

		$this->assertCount(3, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'version', '2');
		$this->assertStatement($statements[2], 'license', 'mit');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_MethodNonDoc()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_MethodNonDoc.php');

		$this->assertCount(3, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'version', '2');
		$this->assertStatement($statements[2], 'method', 'get something');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_InMethod()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_InMethod.php');

		$this->assertCount(4, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
		$this->assertStatement($statements[1], 'version', '2');
		$this->assertStatement($statements[2], 'description', 'some description');
		$this->assertStatement($statements[3], 'method', 'get something');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_AfterClass()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_AfterClass.php');

		$this->assertCount(2, $statements);

		$this->assertStatement($statements[0], 'license', 'mit');
		$this->assertStatement($statements[1], 'title', 'Some words');
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Parser::parse
	 */
	public function testParse_Prefix()
	{
		$object = new \SwaggerGen\Parser\Php\Parser();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

		$statements = $object->parse(__DIR__ . '/ParserTest/testParse_Prefix.php');

		$this->assertCount(1, $statements);

		$this->assertStatement($statements[0], 'title', 'Some words');
	}

	//@todo Including+referencing class files
	//@todo Including+referencing function files
	//@todo self::MethodName
	//@todo static::MethodName(implemented even?
	//@todo $this->MethodName
	//@todo ClassName->MethodName
	//@todo ClassNameMethodName
	//@todo ClassName
	//@todo functionname
}
