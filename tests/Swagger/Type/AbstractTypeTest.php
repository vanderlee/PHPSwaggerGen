<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AbstractTypeTest extends TestCase
{

    protected $parent;

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

    /**
     * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
     */
    public function testCommand_Example()
    {
        $object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

        $object->handleCommand('example', 'foo');

        $this->assertSame(array(
            'type' => 'string',
            'example' => 'foo',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
     */
    public function testCommand_Example_Json()
    {
        $object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

        $object->handleCommand('example', '{foo:bar}');

        $this->assertSame(array(
            'type' => 'string',
            'example' => array(
                'foo' => 'bar',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
     */
    public function testCommand_Example_Json_Multiple_Properties()
    {
        $object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

        $object->handleCommand('example', '{foo:bar,baz:bat}');

        $this->assertSame(array(
            'type' => 'string',
            'example' => array(
                'foo' => 'bar',
                'baz' => 'bat',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
     */
    public function testCommand_Example_Json_String_With_Special_Chars()
    {
        $object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

        $object->handleCommand('example', '"2019-01-01 17:59:59"');

        $this->assertSame(array(
            'type' => 'string',
            'example' => '2019-01-01 17:59:59',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\AbstractType->handleCommand
     */
    public function testCommand_Example_Invalid_Json_Not_Ignored()
    {
        $object = new SwaggerGen\Swagger\Type\StringType($this->parent, 'string');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\StringType', $object);

        $object->handleCommand('example', '2019-01-01{}');

        $this->assertSame(array(
            'type' => 'string',
            'example' => '2019-01-01{}',
        ), $object->toArray());
    }

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\AbstractObject');
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
    }
}
