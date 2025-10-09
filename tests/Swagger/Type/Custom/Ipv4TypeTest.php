<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class Ipv4TypeTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructNotAnIpv4()
    {
        $this->expectException('\SwaggerGen\Exception', "Not an IPv4: 'wrong'");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'wrong');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstruct()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv4Type', $object);

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv4Type::PATTERN,
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructEmptyDefault()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable IPv4 definition: 'ipv4='");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4= ');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructDefaultRange()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid IPv4 default value: '127.0.256.0'");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4=127.0.256.0');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructDefault3Numbers()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid IPv4 default value: '0.0.0'");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4=0.0.0');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructDefault5Numbers()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid IPv4 default value: '0.0.0.0.0'");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4=0.0.0.0.0');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructDefaultUntrimmed()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid IPv4 default value: ' 0.0.0.0.0'");

        new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4= 0.0.0.0.0');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type::__construct
     */
    public function testConstructDefault()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4=123.45.67.89');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv4Type', $object);

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv4Type::PATTERN,
            'default' => '123.45.67.89',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type->handleCommand
     */
    public function testCommandDefaultNoValue()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv4Type', $object);

        $this->expectException('\SwaggerGen\Exception', "Empty IPv4 default");
        $object->handleCommand('default', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\Custom\Ipv4Type->handleCommand
     */
    public function testCommandDefault()
    {
        $object = new SwaggerGen\Swagger\Type\Custom\Ipv4Type($this->parent, 'ipv4');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv4Type', $object);

        $object->handleCommand('default', '123.45.67.89');

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv4Type::PATTERN,
            'default' => '123.45.67.89',
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
