<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Response::__construct
     */
    public function testConstructor_200()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->assertSame(array(
            'description' => 'OK',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Response::__construct
     */
    public function testConstructor_404Type()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 404);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->assertSame(array(
            'description' => 'Not Found',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Response::__construct
     */
    public function testConstructor_Type()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200, 'int');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->assertSame(array(
            'description' => 'OK',
            'schema' => array(
                'type' => 'integer',
                'format' => 'int32',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Response::__construct
     */
    public function testConstructor_Reference()
    {
        $this->parent->handleCommand('model', 'User');

        $object = new \SwaggerGen\Swagger\Response($this->parent, 200, 'User');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->assertSame(array(
            'description' => 'OK',
            'schema' => array(
                '$ref' => '#/definitions/User',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Response::__construct
     */
    public function testConstructor_Description()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, '200', null, 'Fine And Dandy');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->assertSame(array(
            'description' => 'Fine And Dandy',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header_NoType()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->expectException('\SwaggerGen\Exception', "Missing type for header");

        $object->handleCommand('header', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header_NoName()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->expectException('\SwaggerGen\Exception', "Missing name for header type 'int'");

        $object->handleCommand('header', 'int');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header_InvalidType()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->expectException('\SwaggerGen\Exception', "Header type not valid: 'foo'");

        $object->handleCommand('header', 'foo bar');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('header', 'integer bar');

        $this->assertSame(array(
            'description' => 'OK',
            'headers' => array(
                'bar' => array(
                    'type' => 'integer',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header_Description()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('header', 'integer bar Some text');

        $this->assertSame(array(
            'description' => 'OK',
            'headers' => array(
                'bar' => array(
                    'type' => 'integer',
                    'description' => 'Some text',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Header_Multiple()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('header', 'integer bar');
        $object->handleCommand('header', 'string X-Whatever');

        $this->assertSame(array(
            'description' => 'OK',
            'headers' => array(
                'bar' => array(
                    'type' => 'integer',
                ),
                'X-Whatever' => array(
                    'type' => 'string',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_NoName()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->expectException('\SwaggerGen\Exception', "Missing name for example");

        $object->handleCommand('example', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_NoData()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $this->expectException('\SwaggerGen\Exception', "Missing content for example `Foo`");

        $object->handleCommand('example', 'Foo');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Text()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo bar');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => 'bar',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Number()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo 123.45');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => 123.45,
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Json()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo {"bar":"baz"}');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => array(
                    'bar' => 'baz',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Json_Unquoted()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo {bar:baz}');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => array(
                    'bar' => 'baz',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Json_Whitespace()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo   { "bar" : "baz" } ');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => array(
                    'bar' => 'baz',
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->handleCommand
     */
    public function testCommand_Example_Json_Multiple()
    {
        $object = new \SwaggerGen\Swagger\Response($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $object);

        $object->handleCommand('example', 'Foo   "Bar"  ');
        $object->handleCommand('example', 'Baz  false');

        $this->assertSame(array(
            'description' => 'OK',
            'examples' => array(
                'Foo' => 'Bar',
                'Baz' => false,
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_Numeric()
    {
        $this->assertSame(200, \SwaggerGen\Swagger\Response::getCode(200));
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_CaseSensitive()
    {
        $this->assertSame(200, \SwaggerGen\Swagger\Response::getCode('OK'));
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_CaseInsensitive()
    {
        $this->assertSame(200, \SwaggerGen\Swagger\Response::getCode('ok'));
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_Space()
    {
        $this->assertSame(404, \SwaggerGen\Swagger\Response::getCode('not Found'));
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_Crap()
    {
        $this->assertSame(404, \SwaggerGen\Swagger\Response::getCode(' not___F-ou/nd  '));
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Response->getCode
     */
    public function testGetCode_Smashed()
    {
        $this->assertSame(404, \SwaggerGen\Swagger\Response::getCode('nOtfOund'));
    }

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\Swagger');
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
    }

}
