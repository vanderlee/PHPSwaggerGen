<?php

class OperationTest extends PHPUnit_Framework_TestCase
{

	protected $parent;

	protected function setUp()
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\Swagger');
	}

	protected function assertPreConditions()
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::__construct
	 */
	public function testConstructor_NoResponse()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "No response defined for operation");
		$object->toArray();
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testConstructor_Summary()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent, 'Some words');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('error', 404);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $return);

		$this->assertSame(array(
			'summary' => 'Some words',
			'responses' => array(
				404 => array(
					'description' => 'Not Found',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testConstructor_Tag()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent, '', new SwaggerGen\Swagger\Tag($this->parent, 'Operations'));
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('error', 404);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $return);

		$this->assertSame(array(
			'tags' => array(
				'Operations',
			),
			'responses' => array(
				404 => array(
					'description' => 'Not Found',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Summary_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('summary');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Summary()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('summary', 'Some words');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'summary' => 'Some words',
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Description_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('description');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Description()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('description', 'Some words');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'description' => 'Some words',
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Deprecated()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('deprecated');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'deprecated' => true,
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_OperationId()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('id', 'SomeOperation');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'operationId' => 'SomeOperation',
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_OperationId_UniqueInPath()
	{
		$path = $this->parent->handleCommand('endpoint', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);
		
		// First occurance
		$operation = $path->handleCommand('operation', 'GET');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);

		$return = $operation->handleCommand('id', 'SomeOperation');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		// Second occurance
		$operation = $path->handleCommand('operation', 'GET');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);

		$this->setExpectedException('\SwaggerGen\Exception', "Duplicate operation id 'SomeOperation'");
		$operation->handleCommand('id', 'SomeOperation');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_OperationId_UniqueInSwagger()
	{	
		// First occurance
		$path = $this->parent->handleCommand('endpoint', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);
		
		$operation = $path->handleCommand('operation', 'GET');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);

		$return = $operation->handleCommand('id', 'SomeOperation');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		// Second occurance
		$path = $this->parent->handleCommand('endpoint', 'bar');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$operation = $path->handleCommand('operation', 'GET');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);

		$this->setExpectedException('\SwaggerGen\Exception', "Duplicate operation id 'SomeOperation'");
		$operation->handleCommand('id', 'SomeOperation');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Tags_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('tags');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Tags()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('tags', 'black white');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'tags' => array(
				'black',
				'white',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Tags_Append()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent, null, new SwaggerGen\Swagger\Tag($this->parent, 'purple'));
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('tags', 'black white');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('tags', 'black green');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'tags' => array(
				'black',
				'green',
				'purple',
				'white',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Schemes_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('schemes');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Schemes()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('schemes', 'http https');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'schemes' => array(
				'http',
				'https',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Schemes_Append()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('schemes', 'ws wss');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('schemes', 'http ws');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'schemes' => array(
				'http',
				'ws',
				'wss',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Consumes_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('consumes');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Consumes()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('consumes', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'consumes' => array(
				'image/png',
				'text/plain',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Consumes_Append()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('consumes', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('consumes', 'text json');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'consumes' => array(
				'application/json',
				'image/png',
				'text/plain',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Produces_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('produces');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Produces()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('produces', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'produces' => array(
				'image/png',
				'text/plain',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Produces_Append()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('produces', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('produces', 'text json');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'produces' => array(
				'application/json',
				'image/png',
				'text/plain',
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Error_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid error code: ''");
		$object->handleCommand('error');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Error_404()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('error', 404);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $return);

		$this->assertSame(array(
			'responses' => array(
				404 => array(
					'description' => 'Not Found',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Error_404Description()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('error', '404 Not here, Bro!');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Error', $return);

		$this->assertSame(array(
			'responses' => array(
				404 => array(
					'description' => 'Not here, Bro!',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Errors_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$object->handleCommand('errors');

		$this->setExpectedException('\SwaggerGen\Exception', "No response defined for operation");
		$object->toArray();
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Errors()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('errors', '404 403');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$this->assertSame(array(
			'responses' => array(
				403 => array(
					'description' => 'Forbidden',
				),
				404 => array(
					'description' => 'Not Found',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Response_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Invalid response code: ''");
		$object->handleCommand('response');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Response_200()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Response_Definition()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('response', '200 int');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
					'schema' => Array(
						'type' => 'integer',
						'format' => 'int32',
					),
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Response_Description()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('response', '200 int Stuff is returned');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'Stuff is returned',
					'schema' => Array(
						'type' => 'integer',
						'format' => 'int32',
					),
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Require_Empty()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Empty security requirement name");
		$object->handleCommand('require');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Require_Basic_Undefined()
	{
		// precondition
		$swagger = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $swagger);

		$object = new \SwaggerGen\Swagger\Operation($swagger);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('require', 'basic');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->setExpectedException('\SwaggerGen\Exception', "Required security scheme not defined: 'basic'");
		$object->toArray();
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Require_Basic()
	{
		// precondition
		$swagger = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $swagger);
		$swagger->handleCommand('security', 'basic basic');

		$object = new \SwaggerGen\Swagger\Operation($swagger);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('require', 'basic');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertEquals(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
			'security' => array(
				array(
					'basic' => array(),
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Require_Oauth2()
	{
		// precondition
		$swagger = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $swagger);
		$swagger->handleCommand('security', 'oauth oauth2 implicit http://www.test');

		$object = new \SwaggerGen\Swagger\Operation($swagger);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('require', 'oauth user:name user:email');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
			'security' => array(
				array(
					'oauth' => array(
						'user:email',
						'user:name',
					),
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Body_Required()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('body', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\BodyParameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'body',
					'required' => true,
					'schema' => array(
						'type' => 'integer',
						'format' => 'int32',
					),
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Body_Optional()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('body?', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\BodyParameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'body',
					'schema' => array(
						'type' => 'integer',
						'format' => 'int32',
					),
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Path_Required()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('path', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'path',
					'required' => true,
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Query_Required()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('query', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'query',
					'required' => true,
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Query_Optional()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('query?', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'query',
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Header_Required()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('header', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'header',
					'required' => true,
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Header_Optional()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('header?', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'header',
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Form_Required()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('form', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'formData',
					'required' => true,
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Operation::handleCommand
	 */
	public function testHandleCommand_Form_Optional()
	{
		$object = new \SwaggerGen\Swagger\Operation($this->parent);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $object);

		$return = $object->handleCommand('form?', 'int foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Parameter', $return);

		$return = $object->handleCommand('response', '200');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $return);

		$this->assertSame(array(
			'parameters' => array(
				array(
					'name' => 'foo',
					'in' => 'formData',
					'type' => 'integer',
					'format' => 'int32',
				),
			),
			'responses' => array(
				200 => array(
					'description' => 'OK',
				),
			),
				), $object->toArray());
	}

}
