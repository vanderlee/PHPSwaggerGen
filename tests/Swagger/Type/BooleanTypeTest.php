<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class BooleanTypeTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructNotABoolean()
    {
        $this->expectException('\SwaggerGen\Exception', "Not a boolean: 'wrong'");

        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'wrong');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructInvalidDefault()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable boolean definition: 'boolean=null'");

        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean=null');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructNoSpecificationAllowed()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable boolean definition: 'boolean()=true'");

        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean()=true');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructNoRangeAllowed()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable boolean definition: 'boolean[0,1]=true'");

        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean[0,1]=true');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructWithoutDefault()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $this->assertSame(array(
            'type' => 'boolean',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstructTrue()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean=true');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $this->assertSame(array(
            'type' => 'boolean',
            'default' => true,
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType::__construct
     */
    public function testConstruct0()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean=0');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $this->assertSame(array(
            'type' => 'boolean',
            'default' => false,
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType->handleCommand
     */
    public function testCommandDefaultNoValue()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $this->expectException('\SwaggerGen\Exception', "Invalid boolean default: ''");
        $object->handleCommand('default', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType->handleCommand
     */
    public function testCommandDefaultInvalidValue()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $this->expectException('\SwaggerGen\Exception', "Invalid boolean default: 'null'");
        $object->handleCommand('default', 'null');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType->handleCommand
     */
    public function testCommandDefaultFalse()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $object->handleCommand('default', 'false');

        $this->assertSame(array(
            'type' => 'boolean',
            'default' => false,
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\BooleanType->handleCommand
     */
    public function testCommandDefault1()
    {
        $object = new SwaggerGen\Swagger\Type\BooleanType($this->parent, 'boolean');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\BooleanType', $object);

        $object->handleCommand('default', '1');

        $this->assertSame(array(
            'type' => 'boolean',
            'default' => true,
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
