<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\Parser\Php\Parser;
use SwaggerGen\Statement;

class ParserTest extends TestCase
{

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::__construct
     */
    public function testConstructor_Empty()
    {
        $object = new Parser();
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
     * Test all open-curlies are (ac-)counted for in functions
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_CurlyBraceFunction()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_CurlyBraceFunction.php');
        $this->assertCount(6, $statements);
        $this->assertStatement($statements[0], 'title', 'CurlyBraceFunction');
    }

    /**
     * Test if the statement matches the expected values
     * @param Statement $statement
     * @param string $command
     * @param string $data
     */
    private function assertStatement($statement, $command, $data = '')
    {
        $this->assertInstanceOf('\SwaggerGen\Statement', $statement);
        $this->assertSame($command, $statement->getCommand());
        $this->assertSame($data, $statement->getData());
    }

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_NoMethods()
    {
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
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
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_Prefix.php');

        $this->assertCount(1, $statements);

        $this->assertStatement($statements[0], 'title', 'Some words');
    }

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_PropertyReadOnly()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_PropertyReadOnly.php');

        $this->assertCount(4, $statements);

        $this->assertStatement($statements[0], 'title', 'Some words');
        $this->assertStatement($statements[1], 'version', '2');
        $this->assertStatement($statements[2], 'definition', 'Foo');
        $this->assertStatement($statements[3], 'property!', 'string bar');
    }

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_PropertyOptional()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_PropertyOptional.php');

        $this->assertCount(4, $statements);

        $this->assertStatement($statements[0], 'title', 'Some words');
        $this->assertStatement($statements[1], 'version', '2');
        $this->assertStatement($statements[2], 'definition', 'Foo');
        $this->assertStatement($statements[3], 'property?', 'string bar');
    }

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_Minimal()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_Minimal.php');

        $this->assertCount(4, $statements);

        $this->assertStatement($statements[0], 'title', 'Minimal');
        $this->assertStatement($statements[1], 'api', 'MyApi Example');
        $this->assertStatement($statements[2], 'endpoint', '/endpoint');
        $this->assertStatement($statements[3], 'method', 'GET Something');
    }

    /**
     * Tests FunctionName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeFunction()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeFunction.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Test if the statement matches the expected values
     * @param array[] $expected
     * @param SwaggerGen\Parser\Php\Statement[] $statements
     */
    private function assertStatements(array $expected, array $statements)
    {
        $this->assertCount(count($expected), $statements, join("\n", $statements));
        foreach ($expected as $index => $command) {
            $statement = $statements[$index];
            $this->assertInstanceOf('\SwaggerGen\Statement', $statement);
            $this->assertSame($command[0], $statement->getCommand());
            $this->assertSame($command[1], $statement->getData());
        }
    }

    /**
     * Tests $this->MethodName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeThisMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeThisMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Class->MethodName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeObjectMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeObjectMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests self::MethodName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeSelfMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeSelfMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Class::MethodName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeClassMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeClassMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests static::MethodName
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_StaticMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_StaticMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests $this->MethodName with inheritance
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeInheritedThisMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeInheritedThisMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Class->MethodName with inheritance
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_SeeInheritedObjectMethod()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_SeeInheritedObjectMethod.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Autoloading other class when referenced
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_Autoload_Parse()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_Autoload.php', array(
            __DIR__ . '/ParserTest',
        ));

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Autoloading other class when referenced
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_Autoload_Construct()
    {
        $object = new Parser(array(
            __DIR__ . '/ParserTest',
        ));
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_Autoload.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * Tests Autoloading other class when referenced
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_Autoload_AddDirs()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $object->addDirs(array(
            __DIR__ . '/ParserTest',
        ));

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_Autoload.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('method', 'GET Something'),
            array('error', '400'),
        ), $statements);
    }

    /**
     * @covers \SwaggerGen\Parser\Php\Parser::parse
     */
    public function testParse_XTag()
    {
        $object = new Parser();
        $this->assertInstanceOf('\SwaggerGen\Parser\Php\Parser', $object);

        $object->addDirs(array(
            __DIR__ . '/ParserTest',
        ));

        $statements = $object->parse(__DIR__ . '/ParserTest/testParse_XTag.php');

        $this->assertStatements(array(
            array('api', 'MyApi Example'),
            array('endpoint', '/endpoint'),
            array('x-something', 'else'),
            array('method', 'GET Something'),
        ), $statements);
    }

}
