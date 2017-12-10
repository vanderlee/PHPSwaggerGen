<?php

class Ipv6TypeTest extends SwaggerGen_TestCase
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
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructNotAnIpv6()
	{
		$this->expectException('\SwaggerGen\Exception', "Not an IPv6: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstruct()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv6Type', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv6Type::PATTERN,
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructEmptyDefault()
	{
		$this->expectException('\SwaggerGen\Exception', "Unparseable IPv6 definition: 'ipv6='");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6= ');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefaultTooManyDigits()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid IPv6 default value: '12001:0db8:85a3:0000:1319:8a2e:0370:7344'");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6=12001:0db8:85a3:0000:1319:8a2e:0370:7344');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefaultTooFewParts()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid IPv6 default value: '0db8:85a3:0000:1319:8a2e:0370:7344'");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6=0db8:85a3:0000:1319:8a2e:0370:7344');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefaultTooManyParts()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid IPv6 default value: '2001:2001:0db8:85a3:0000:1319:8a2e:0370:7344'");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6=2001:2001:0db8:85a3:0000:1319:8a2e:0370:7344');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefaultUntrimmed()
	{
		$this->expectException('\SwaggerGen\Exception', "Invalid IPv6 default value: ' 2001:0db8:85a3:0000:1319:8a2e:0370:7344'");

		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6= 2001:0db8:85a3:0000:1319:8a2e:0370:7344');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefault()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6=2001:0db8:85a3:0000:1319:8a2e:0370:7344');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv6Type', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv6Type::PATTERN,
			'default' => '2001:0db8:85a3:0000:1319:8a2e:0370:7344',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type::__construct
	 */
	public function testConstructDefaultNoZeroes()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6=2001:0db8:85a3::1319:8a2e:0370:7344');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv6Type', $object);

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv6Type::PATTERN,
			'default' => '2001:0db8:85a3::1319:8a2e:0370:7344',
				), $object->toArray());
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type->handleCommand
	 */
	public function testCommandDefaultNoValue()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv6Type', $object);

		$this->expectException('\SwaggerGen\Exception', "Empty IPv6 default");
		$object->handleCommand('default', '');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\Custom\Ipv6Type->handleCommand
	 */
	public function testCommandDefault()
	{
		$object = new SwaggerGen\Swagger\Type\Custom\Ipv6Type($this->parent, 'ipv6');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\Custom\Ipv6Type', $object);

		$object->handleCommand('default', '2001:0db8:85a3:0000:1319:8a2e:0370:7344');

		$this->assertSame(array(
			'type' => 'string',
			'pattern' => \SwaggerGen\Swagger\Type\Custom\Ipv6Type::PATTERN,
			'default' => '2001:0db8:85a3:0000:1319:8a2e:0370:7344',
				), $object->toArray());
	}

}
