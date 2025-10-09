<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\Swagger\AbstractObject;

class ErrorTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Error::__construct
     */
    public function testConstructor_200(): void
    {
        $object = new \SwaggerGen\Swagger\Error($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

        $this->assertSame(array(
            'description' => 'OK',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Error::__construct
     */
    public function testConstructor_404(): void
    {
        $object = new \SwaggerGen\Swagger\Error($this->parent, 404);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

        $this->assertSame(array(
            'description' => 'Not Found',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Error::__construct
     */
    public function testConstructor_Description(): void
    {
        $object = new \SwaggerGen\Swagger\Error($this->parent, '200', 'Fine And Dandy');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

        $this->assertSame(array(
            'description' => 'Fine And Dandy',
        ), $object->toArray());
    }

    /**
     * Just checks that the `header` command is inherited. Actual tests for
     * this command are in `ResponseTest`
     * @covers \SwaggerGen\Swagger\Type\Error->handleCommand
     */
    public function testHandleCommand_Header(): void
    {
        $object = new \SwaggerGen\Swagger\Error($this->parent, 200);

        $this->assertInstanceOf('\SwaggerGen\Swagger\Error', $object);

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

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass(AbstractObject::class);
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
    }

}
