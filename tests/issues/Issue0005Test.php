<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class Issue0005Test extends TestCase
{

    /**
     * @covers \SwaggerGen\Swagger\Swagger::__construct
     */
    public function testHandleCommand_Model_Property()
    {
        $object = new \SwaggerGen\Swagger\Swagger;
        $this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

        $schema = $object->handleCommand('model', 'foo');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $schema);

        $schema->handleCommand('property', 'string bar Some words here');

        $path = $object->handleCommand('endpoint');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

        $this->assertSame(array(
            'swagger' => '2.0',
            'info' => array(
                'title' => 'undefined',
                'version' => '0',
            ),
            'paths' => array(
                '/' => array(),
            ),
            'definitions' => array(
                'foo' => array(
                    'type' => 'object',
                    'required' => array(
                        'bar',
                    ),
                    'properties' => array(
                        'bar' => array(
                            'type' => 'string',
                            'description' => 'Some words here',
                        ),
                    ),
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Swagger::__construct
     */
    public function testHandleCommand_Model_DuplicateProperties()
    {
        $object = new \SwaggerGen\Swagger\Swagger();
        $this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

        $schema = $object->handleCommand('model', 'foo');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $schema);

        $schema->handleCommand('property', 'string bar Some words here');
        $schema->handleCommand('property', 'integer bar Some other words');

        $path = $object->handleCommand('endpoint');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

        $this->assertSame(array(
            'swagger' => '2.0',
            'info' => array(
                'title' => 'undefined',
                'version' => '0',
            ),
            'paths' => array(
                '/' => array(),
            ),
            'definitions' => array(
                'foo' => array(
                    'type' => 'object',
                    'required' => array(
                        'bar',
                    ),
                    'properties' => array(
                        'bar' => array(
                            'type' => 'integer',
                            'format' => 'int32',
                            'description' => 'Some other words',
                        ),
                    ),
                ),
            ),
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Operation::handleCommand
     */
    public function testHandleCommand_Query_Duplicate()
    {
        $object = new \SwaggerGen\Swagger\Swagger;
        $this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

        $operation = new \SwaggerGen\Swagger\Operation($object);
        $this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);

        $parameter = $operation->handleCommand('query', 'int foo Some text');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $parameter);

        $parameter = $operation->handleCommand('query', 'string foo Other text');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $parameter);

        $response = $operation->handleCommand('response', '200');
        $this->assertInstanceOf('\SwaggerGen\Swagger\Response', $response);

        $this->assertSame(array(
            'parameters' => array(
                array(
                    'name' => 'foo',
                    'in' => 'query',
                    'description' => 'Other text',
                    'required' => true,
                    'type' => 'string',
                ),
            ),
            'responses' => array(
                200 => array(
                    'description' => 'OK',
                ),
            ),
        ), $operation->toArray());
    }

}
