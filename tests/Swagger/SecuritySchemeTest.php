<?php

class SecuritySChemeTest extends PHPUnit_Framework_TestCase
{

	protected $parent;

	protected function setUp()
	{
		$this->parent = $this->getMockForAbstractClass('\SwaggerGen\Swagger\AbstractObject');
	}

	protected function assertPreConditions()
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorUnknownType()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "Security scheme type must be either 'basic', 'apiKey' or 'oauth2', not 'wrong'"
		);

		new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorBasic()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'basic');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'basic',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorBasicDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'basic', 'Some text');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'basic',
			'description' => 'Some text',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorApiKeyNoName()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "ApiKey in must be either 'query' or 'header', not ''"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'apikey');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorApiKeyNoIn()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "ApiKey in must be either 'query' or 'header', not ''"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'apikey', 'Name');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorApiKeyWrongIn()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "ApiKey in must be either 'query' or 'header', not 'bad'"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'apikey', 'Name bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorApiKey()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'apikey', 'Name query');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'apiKey',
			'name' => 'Name',
			'in' => 'query',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorApiKeyDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'apikey', 'Name query Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'apiKey',
			'description' => 'Some words',
			'name' => 'Name',
			'in' => 'query',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2NoFlow()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 flow must be either 'implicit', 'password', 'application' or 'accesscode', not ''"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2WrongFlow()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 flow must be either 'implicit', 'password', 'application' or 'accesscode', not 'flow'"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'flow');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ImplicitNoUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 authorization URL invalid: ''"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'implicit');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ImplicitBadUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 authorization URL invalid: 'bad'"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'implicit bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ImplicitUrl()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'implicit http://www.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'implicit',
			'authorizationUrl' => 'http://www.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ImplicitUrlDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'implicit http://www.test Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'description' => 'Some words',
			'flow' => 'implicit',
			'authorizationUrl' => 'http://www.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2PasswordNoUrl1()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 authorization URL invalid: ''");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2PasswordBadUrl1()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 authorization URL invalid: 'bad'"
		);

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2PasswordNoUrl2()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: ''");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password http://auth.test');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2PasswordBadUrl2()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: 'bad'");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password http://auth.test bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2Password()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password http://auth.test http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'password',
			'authorizationUrl' => 'http://auth.test',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2PasswordDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'password http://auth.test http://token.test Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'description' => 'Some words',
			'flow' => 'password',
			'authorizationUrl' => 'http://auth.test',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ApplicationNoUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: ''");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'application');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ApplicationBadUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: 'bad'");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'application bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2Application()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'application http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'application',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2ApplicationDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'application http://token.test Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'description' => 'Some words',
			'flow' => 'application',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2AccesscodeNoUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: ''");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2AccesscodeBadUrl()
	{
		$this->setExpectedException('\SwaggerGen\Exception', "OAuth2 token URL invalid: 'bad'");

		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode bad');
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2Accesscode()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme::__construct
	 */
	public function testConstructorOauth2AccesscodeDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->assertSame(array(
			'type' => 'oauth2',
			'description' => 'Some words',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$object->handleCommand('description', 'Some words');

		$this->assertSame(array(
			'type' => 'oauth2',
			'description' => 'Some words',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandDescriptionEmpty()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test Some words');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$object->handleCommand('description', '');

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandScopeNotOauth()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'basic');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$this->setExpectedException('\SwaggerGen\Exception', "Cannot set scope on type 'basic'");

		$object->handleCommand('scope', 'scope1');
	}
	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandScope()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$object->handleCommand('scope', 'scope1');

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
			'scopes' => array(
				'scope1' => '',
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandScopeDescription()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$object->handleCommand('scope', 'scope1 Some text');

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
			'scopes' => array(
				'scope1' => 'Some text',
			)
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\SecurityScheme->handleCommand
	 */
	public function testCommandScopes()
	{
		$object = new \SwaggerGen\Swagger\SecurityScheme($this->parent, 'oauth2', 'accesscode http://token.test');

		$this->assertInstanceOf('\SwaggerGen\Swagger\SecurityScheme', $object);

		$object->handleCommand('scope', 'scope2 Some text');
		$object->handleCommand('scope', 'scope1 Some text');

		$this->assertSame(array(
			'type' => 'oauth2',
			'flow' => 'accessCode',
			'tokenUrl' => 'http://token.test',
			'scopes' => array(
				'scope2' => 'Some text',
				'scope1' => 'Some text',
			)
				), $object->toArray());
	}
}
