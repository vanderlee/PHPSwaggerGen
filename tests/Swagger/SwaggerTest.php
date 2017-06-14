<?php

class SwaggerTest extends PHPUnit\Framework\TestCase
{

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "No path defined");
		$object->toArray();
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testConstructor_Empty_WithEndPoint()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

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
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testConstructor_Empty_Host()
	{
		$object = new \SwaggerGen\Swagger\Swagger('http://localhost');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'host' => 'http://localhost',
			'paths' => array(
				'/' => array(),
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testConstructor_Empty_HostBasepath()
	{
		$object = new \SwaggerGen\Swagger\Swagger('http://localhost', 'api');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'host' => 'http://localhost',
			'basePath' => 'api',
			'paths' => array(
				'/' => array(),
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Info_Pass()
	{
		$object = new \SwaggerGen\Swagger\Swagger('http://localhost', 'api');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$object->handleCommand('title', 'This is the title');
		$object->handleCommand('description', 'This is the description');
		$object->handleCommand('license', 'BSD');
		$object->handleCommand('terms', 'These are the terms');
		$object->handleCommand('version', '1.2.3a');
		$object->handleCommand('title', 'This is the title');
		$object->handleCommand('contact', 'Arthur D. Author http://example.test arthur@author.test');

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array('title' => 'This is the title',
				'description' => 'This is the description',
				'termsOfService' => 'These are the terms',
				'contact' => array(
					'name' => 'Arthur D. Author',
					'url' => 'http://example.test',
					'email' => 'arthur@author.test',
				),
				'license' => array(
					'name' => 'BSD',
					'url' => 'https://opensource.org/licenses/BSD-2-Clause',
				),
				'version' => '1.2.3a',
			),
			'host' => 'http://localhost',
			'basePath' => 'api',
			'paths' => array(
				'/' => array(),
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Schemes_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('schemes');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

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
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Schemes()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('schemes', 'http https');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'schemes' => array(
				'http',
				'https',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Schemes_Append()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('schemes', 'ws wss');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$return = $object->handleCommand('schemes', 'http ws');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'schemes' => array(
				'http',
				'ws',
				'wss',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Consumes_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('consumes');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

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
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Consumes()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('consumes', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'consumes' => array(
				'image/png',
				'text/plain',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Consumes_Append()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('consumes', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$return = $object->handleCommand('consumes', 'text json');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'consumes' => array(
				'application/json',
				'image/png',
				'text/plain',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Produces_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('produces');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

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
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Produces()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('produces', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'produces' => array(
				'image/png',
				'text/plain',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Produces_Append()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('produces', 'image/png text');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$return = $object->handleCommand('produces', 'text json');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'produces' => array(
				'application/json',
				'image/png',
				'text/plain',
			),
			'paths' => array(
				'/' => array(),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Tag_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing tag name");
		$object->handleCommand('tag');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Tag()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('tag', 'Users');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);
		$operation = $path->handleCommand('method', 'get');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
		$response = $operation->handleCommand('response', 200);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $response);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/' => array(
					'get' => array(
						'responses' => array(
							200 => array(
								'description' => 'OK',
							),
						),
					),
				),
			),
			'tags' => array(
				array(
					'name' => 'Users',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Api()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('api', 'Users');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);
		$operation = $path->handleCommand('method', 'get');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Operation', $operation);
		$response = $operation->handleCommand('response', 200);
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $response);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/' => array(
					'get' => array(
						'tags' => array(
							'Users',
						),
						'responses' => array(
							200 => array(
								'description' => 'OK',
							),
						),
					),
				),
			),
			'tags' => array(
				array(
					'name' => 'Users',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::handleCommand
	 */
	public function testHandleCommand_Tag_Repeat()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('tag', 'Users Info A');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $return);

		$return = $object->handleCommand('tag', 'Users Info B');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Tag', $return);

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
			'tags' => array(
				array(
					'name' => 'Users',
					'description' => 'Info A',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_EndPoint_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

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
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_EndPoint()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint', 'users/:userid/rights');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/users/:userid/rights' => array(),
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_EndPoint_Tag()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint', 'users/:userid/rights Users');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/users/:userid/rights' => array(),
			),
			'tags' => array(
				array(
					'name' => 'Users',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_EndPoint_Tag_NoDescriptionOverwrite()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('api', 'Users something here');
		$path = $object->handleCommand('endpoint', 'users/:userid/rights Users');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/users/:userid/rights' => array(),
			),
			'tags' => array(
				array(
					'name' => 'Users',
					'description' => 'something here',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_EndPoint_Tag_Description()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$path = $object->handleCommand('endpoint', 'users/:userid/rights Users Description here');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertSame(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/users/:userid/rights' => array(),
			),
			'tags' => array(
				array(
					'name' => 'Users',
					'description' => 'Description here',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Security_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing security name");
		$path = $object->handleCommand('security');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Security_MissingType()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing security type");
		$path = $object->handleCommand('security', 'foo');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Security_BadType()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Security scheme type must be either 'basic', 'apiKey' or 'oauth2', not 'bad'");
		$path = $object->handleCommand('security', 'foo bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Security()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$security = $object->handleCommand('security', 'foo basic');
		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $security);

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
			'securityDefinitions' => array(
				'foo' => array(
					'type' => 'basic',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Require_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing require name");
		$object->handleCommand('require');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Require_Undefined()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('require', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->setExpectedException('\SwaggerGen\Exception', "Required security scheme not defined: 'foo'");
		$object->toArray();
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Require()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$return = $object->handleCommand('require', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $return);

		$security = $object->handleCommand('security', 'foo basic');
		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $security);

		$path = $object->handleCommand('endpoint');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Path', $path);

		$this->assertEquals(array(
			'swagger' => '2.0',
			'info' => array(
				'title' => 'undefined',
				'version' => '0',
			),
			'paths' => array(
				'/' => array(),
			),
			'securityDefinitions' => array(
				'foo' => array(
					'type' => 'basic',
				),
			),
			'security' => array(
				array(
					'foo' => array(),
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Definition_Empty()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing definition name");
		$object->handleCommand('definition');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Definition_NoName()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Missing definition name");
		$object->handleCommand('definition', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Definition()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$schema = $object->handleCommand('definition', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $schema);


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
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Model()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$schema = $object->handleCommand('model', 'foo');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Schema', $schema);

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
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Response_NoType()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Response definition missing description");
		$response = $object->handleCommand('response', 'NotFound');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Response_NoDescription()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Response definition missing description");
		$response = $object->handleCommand('response', 'NotFound null');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Response_WithoutDefinition()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$response = $object->handleCommand('response', 'NotFound null Entity not found');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $response);

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
			'responses' => array(
				'NotFound' => array(
					'description' => 'Entity not found',
				),
			),
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Swagger::__construct
	 */
	public function testHandleCommand_Response_WithDefinition()
	{
		$object = new \SwaggerGen\Swagger\Swagger();
		$this->assertInstanceOf('\SwaggerGen\Swagger\Swagger', $object);

		$response = $object->handleCommand('response', 'NotFound int Entity not found');
		$this->assertInstanceOf('\SwaggerGen\Swagger\Response', $response);

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
			'responses' => array(
				'NotFound' => array(
					'description' => 'Entity not found',
					'schema' => array(
						'type' => 'integer',
						'format' => 'int32'
					),
				),
			),
				), $object->toArray());
	}

}
