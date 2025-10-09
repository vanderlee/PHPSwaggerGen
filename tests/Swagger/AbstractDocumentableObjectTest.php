<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AbstractDocumentableObjectTest extends TestCase
{

    protected $parent;

    /**
     * @covers \SwaggerGen\Swagger\Tag::__construct
     * @covers \SwaggerGen\Swagger\AbstractDocumentableObject->handleCommand
     */
    public function testCommandDocWithoutDescription()
    {
        $object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

        $this->assertSame(array(
            'name' => 'Name',
        ), $object->toArray());

        $object->handleCommand('doc', 'http://example.test');

        $this->assertSame(array(
            'name' => 'Name',
            'externalDocs' => array(
                'url' => 'http://example.test',
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Tag::__construct
     * @covers \SwaggerGen\Swagger\AbstractDocumentableObject->handleCommand
     */
    public function testCommandDocWithDescription()
    {
        $object = new \SwaggerGen\Swagger\Tag($this->parent, 'Name');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $object);

        $this->assertSame(array(
            'name' => 'Name',
        ), $object->toArray());

        $object->handleCommand('doc', 'http://example.test Some words here');

        $this->assertSame(array(
            'name' => 'Name',
            'externalDocs' => array(
                'url' => 'http://example.test',
                'description' => 'Some words here',
            ),
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
