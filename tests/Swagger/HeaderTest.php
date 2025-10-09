<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\Swagger\AbstractObject;

class HeaderTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Header::__construct
     */
    public function testConstructorType(): void
    {
        $object = new \SwaggerGen\Swagger\Header($this->parent, 'integer');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Header', $object);

        $this->assertSame(array(
            'type' => 'integer',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Header::__construct
     */
    public function testConstructorInvalidType()
    {
        $this->expectException('\SwaggerGen\Exception', "Header type not valid: 'BadType'");

        new \SwaggerGen\Swagger\Header($this->parent, 'BadType');
    }

    /**
     * @covers \SwaggerGen\Swagger\Header::__construct
     */
    public function testConstructorNoDescription()
    {
        $object = new \SwaggerGen\Swagger\Header($this->parent, 'integer');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Header', $object);

        $this->assertSame(array(
            'type' => 'integer',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Header::__construct
     */
    public function testConstructorBlankDescription()
    {
        $object = new \SwaggerGen\Swagger\Header($this->parent, 'integer', '');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Header', $object);

        $this->assertSame(array(
            'type' => 'integer',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Header::__construct
     */
    public function testConstructorFull()
    {
        $object = new \SwaggerGen\Swagger\Header($this->parent, 'integer', 'descriptive text');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Header', $object);

        $this->assertSame(array(
            'type' => 'integer',
            'description' => 'descriptive text',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Header->handleCommand
     */
    public function testCommandDescription()
    {
        $object = new \SwaggerGen\Swagger\Header($this->parent, 'integer', 'descriptive text');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Header', $object);

        $object->handleCommand('description', 'Some other lines');

        $this->assertSame(array(
            'type' => 'integer',
            'description' => 'Some other lines',
        ), $object->toArray());
    }

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass(AbstractObject::class);
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
    }
}
