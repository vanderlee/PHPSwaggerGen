<?php

class PathTest extends SwaggerGen_TestCase
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
     * @covers \SwaggerGen\Swagger\Path::__construct
     */
    public function testConstructor_Empty()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent);
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $this->assertSame([], $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::__construct
     */
    public function testConstructor_Tag()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $this->assertSame([], $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_Method_Empty()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $this->expectException('\SwaggerGen\Exception', "Unrecognized operation method ''");
        $return = $object->handleCommand('method');
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_Method_Unknown()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $this->expectException('\SwaggerGen\Exception', "Unrecognized operation method 'foo'");
        $return = $object->handleCommand('method', 'foo');
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_Method_Get()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $operation = $object->handleCommand('method', 'get');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $this->assertSame([
            'get' => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
        ], $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_Method_Description()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_Operation_Get()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $operation = $object->handleCommand('operation', 'get');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $this->assertSame([
            'get' => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
        ], $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Path::handleCommand
     */
    public function testHandleCommand_MethodOrdering()
    {
        $object = new \SwaggerGen\Swagger\Path($this->parent, new SwaggerGen\Swagger\Tag($this->parent, 'Tag'));
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $object);

        $operation = $object->handleCommand('method', 'post');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'delete');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'patch');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'put');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'head');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'get');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $operation = $object->handleCommand('method', 'options');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
        $operation->handleCommand('response', 200);

        $this->assertSame([
            'get'     => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'put'     => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'post'    => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'delete'  => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'options' => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'head'    => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
            'patch'   => [
                'tags'      => [
                    'Tag',
                ],
                'responses' => [
                    200 => [
                        'description' => 'OK',
                    ],
                ],
            ],
        ], $object->toArray());
    }

}
