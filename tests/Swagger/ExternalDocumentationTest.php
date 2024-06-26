<?php

class ExternalDocumentationTest extends SwaggerGen_TestCase
{

    protected $parent;

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\AbstractObject');
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
    }

    /**
     * @covers \SwaggerGen\Swagger\ExternalDocumentation::__construct
     */
    public function testConstructorUrl(): void
    {
        $object = new \SwaggerGen\Swagger\ExternalDocumentation($this->parent, 'http://example.test');

        $this->assertInstanceOf('\SwaggerGen\Swagger\ExternalDocumentation', $object);

        $this->assertSame(array(
            'url' => 'http://example.test',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\ExternalDocumentation::__construct
     */
    public function testConstructorFull()
    {
        $object = new \SwaggerGen\Swagger\ExternalDocumentation($this->parent, 'http://example.test', 'Descriptive text');

        $this->assertInstanceOf('\SwaggerGen\Swagger\ExternalDocumentation', $object);

        $this->assertSame(array(
            'url' => 'http://example.test',
            'description' => 'Descriptive text',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\ExternalDocumentation::handleCommand
     */
    public function testCommandUrl()
    {
        $object = new \SwaggerGen\Swagger\ExternalDocumentation($this->parent, 'http://example.test', 'Descriptive text');

        $this->assertInstanceOf('\SwaggerGen\Swagger\ExternalDocumentation', $object);

        $object->handleCommand('url', 'http://other.test');

        $this->assertSame(array(
            'url' => 'http://other.test',
            'description' => 'Descriptive text',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\ExternalDocumentation::handleCommand
     */
    public function testCommandDescription()
    {
        $object = new \SwaggerGen\Swagger\ExternalDocumentation($this->parent, 'http://example.test', 'Descriptive text');

        $this->assertInstanceOf('\SwaggerGen\Swagger\ExternalDocumentation', $object);

        $object->handleCommand('description', 'Some other words');

        $this->assertSame(array(
            'url' => 'http://example.test',
            'description' => 'Some other words',
        ), $object->toArray());
    }

}
