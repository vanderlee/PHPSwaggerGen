<?php

class InfoTest extends PHPUnit\Framework\TestCase
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
	 * @covers \SwaggerGen\Swagger\Info
	 */
	public function testNoConstructor()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$this->assertSame(array(
			'title' => 'undefined',
			'version' => '0',
		), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandTitle()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('title', 'This is the title');

		$this->assertSame(array(
			'title' => 'This is the title',
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandDescription()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('description', 'This is the description');

		$this->assertSame(array(
			'title' => 'undefined',
			'description' => 'This is the description',
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandVersion()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('version', '1.2.3a');

		$this->assertSame(array(
			'title' => 'undefined',
			'version' => '1.2.3a',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandTermsofservice()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('termsofservice', 'These are the terms');

		$this->assertSame(array(
			'title' => 'undefined',
			'termsOfService' => 'These are the terms',
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandTerms()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('terms', 'These are the terms');

		$this->assertSame(array(
			'title' => 'undefined',
			'termsOfService' => 'These are the terms',
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandContactNameUrlEmail()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('contact', 'Arthur D. Author http://example.test arthur@author.test');

		$this->assertSame(array(
			'title' => 'undefined',
			'contact' => array(
				'name' => 'Arthur D. Author',
				'url' => 'http://example.test',
				'email' => 'arthur@author.test',
			),
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandContactNameEmailNameUrlName()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('contact', 'Arthur arthur@author.test D. http://example.test Author    ');

		$this->assertSame(array(
			'title' => 'undefined',
			'contact' => array(
				'name' => 'Arthur D. Author',
				'url' => 'http://example.test',
				'email' => 'arthur@author.test',
			),
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandLicenseNameUrl()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('license', 'NAME http://example.test');

		$this->assertSame(array(
			'title' => 'undefined',
			'license' => array(
				'name' => 'NAME',
				'url' => 'http://example.test',
			),
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandLicenseUrlName()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('license', 'http://example.test NAME');

		$this->assertSame(array(
			'title' => 'undefined',
			'license' => array(
				'name' => 'NAME',
				'url' => 'http://example.test',
			),
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandLicenseShorthand()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('license', 'BSD');

		$this->assertSame(array(
			'title' => 'undefined',
			'license' => array(
				'name' => 'BSD',
				'url' => 'https://opensource.org/licenses/BSD-2-Clause',
			),
			'version' => '0',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Info::handleCommand
	 */
	public function testCommandAll()
	{
		$object = new \SwaggerGen\Swagger\Info;
		$this->assertInstanceOf('\SwaggerGen\Swagger\Info', $object);

		$object->handleCommand('description', 'This is the description');
		$object->handleCommand('license', 'BSD');
		$object->handleCommand('terms', 'These are the terms');
		$object->handleCommand('version', '1.2.3a');
		$object->handleCommand('title', 'This is the title');
		$object->handleCommand('contact', 'Arthur D. Author http://example.test arthur@author.test');

		$this->assertSame(array(
			'title' => 'This is the title',
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
				), $object->toArray());
	}

}
