<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MacTypeTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructNotAnMac()
    {
        $this->expectException('\SwaggerGen\Exception', "Not a MAC: 'wrong'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'wrong');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstruct()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\MacType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\MacType::PATTERN,
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructEmptyDefault()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable MAC definition: 'mac='");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac= ');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefaultTooFewBytes()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid MAC default value: 'FF:FF:FF:FF:FF'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac=FF:FF:FF:FF:FF');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefaultTooManyBytes()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid MAC default value: 'FF:FF:FF:FF:FF:FF:FF'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac=FF:FF:FF:FF:FF:FF:FF');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefaultTooFewDigits()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid MAC default value: 'F:FF:FF:FF:FF:FF'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac=F:FF:FF:FF:FF:FF');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefaultTooManyDigits()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid MAC default value: 'FFF:FF:FF:FF:FF:FF'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac=FFF:FF:FF:FF:FF:FF');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefaultUntrimmed()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid MAC default value: ' FF:FF:FF:FF:FF:FF'");

        new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac= FF:FF:FF:FF:FF:FF');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType::__construct
     */
    public function testConstructDefault()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac=FF:FF:FF:FF:FF:FF');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\MacType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\MacType::PATTERN,
            'default' => 'FF:FF:FF:FF:FF:FF',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType->handleCommand
     */
    public function testCommandDefaultNoValue()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\MacType', $object);

        $this->expectException('\SwaggerGen\Exception', "Empty MAC default");
        $object->handleCommand('default', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\MacType->handleCommand
     */
    public function testCommandDefault()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\MacType($this->parent, 'mac');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\MacType', $object);

        $object->handleCommand('default', 'FF:FF:FF:FF:FF:FF');

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\MacType::PATTERN,
            'default' => 'FF:FF:FF:FF:FF:FF',
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
