<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\Exception as SwaggerException;
use SwaggerGen\Swagger\AbstractObject;
use SwaggerGen\Swagger\Type\StringType;

class StringTypeTest extends TestCase
{

    protected $parent;

    /**
     * @covers StringType::__construct
     */
    public function testConstructNotAString()
    {
        $this->expectException(SwaggerException::class, "Not a string: 'wrong'");

        $object = new StringType($this->parent, 'wrong');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructEmptyRange()
    {
        $this->expectException(SwaggerException::class, "Empty string range: 'string[,]=1'");

        $object = new StringType($this->parent, 'string[,]=1');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructDefaultTooLongInclusive()
    {
        $this->expectException(SwaggerException::class, "Default string length beyond maximum: 'long'");

        $object = new StringType($this->parent, 'string[,3]=long');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructDefaultTooLongExclusive()
    {
        $this->expectException(SwaggerException::class, "Default string length beyond maximum: 'long'");

        $object = new StringType($this->parent, 'string[,4>=long');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructDefaultTooShortInclusive()
    {
        $this->expectException(SwaggerException::class, "Default string length beyond minimum: 'short'");

        $object = new StringType($this->parent, 'string[6,]=short');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructDefaultTooShortExclusive()
    {
        $this->expectException(SwaggerException::class, "Default string length beyond minimum: 'short'");

        $object = new StringType($this->parent, 'string<5,]=short');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructString()
    {
        $object = new StringType($this->parent, 'string');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructStringEmptyDefault()
    {
        $this->expectException(SwaggerException::class, "Unparseable string definition: 'string='");

        $object = new StringType($this->parent, 'string= ');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructStringDefaultLengthInclusive()
    {
        $object = new StringType($this->parent, 'string[4,4]=word');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'word',
            'minLength' => 4,
            'maxLength' => 4,
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructStringDefaultLengthExclusive()
    {
        $object = new StringType($this->parent, 'string<3,5>=word');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'word',
            'minLength' => 4,
            'maxLength' => 4,
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructStringDefaultWithWhitespace()
    {
        $object = new StringType($this->parent, 'string="white space"');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'white space',
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructBinary()
    {
        $object = new StringType($this->parent, 'binary');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'binary',
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructEnumRange()
    {
        $this->expectException(SwaggerException::class, "Range not allowed in enumeration definition: 'enum(a,b)[,]'");

        $object = new StringType($this->parent, 'enum(a,b)[,]');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructEnumInvalidDefault()
    {
        $this->expectException(SwaggerException::class, "Invalid enum default: 'c'");

        $object = new StringType($this->parent, 'enum(a,b)=c');
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructEnum()
    {
        $object = new StringType($this->parent, 'enum(a,b)');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'enum' => array('a', 'b'),
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructEnumWithDefault()
    {
        $object = new StringType($this->parent, 'enum(a,b)=a');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'a',
            'enum' => array('a', 'b'),
        ), $object->toArray());
    }

    /**
     * @covers StringType::__construct
     */
    public function testConstructPattern()
    {
        $object = new StringType($this->parent, 'string([a-z])');

        $this->assertInstanceOf(StringType::class, $object);

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => '[a-z]',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
     */
    public function testCommandDefaultNoValue()
    {
        $object = new StringType($this->parent, 'string');

        $this->assertInstanceOf(StringType::class, $object);

        $this->expectException(SwaggerException::class, "Empty string default");
        $object->handleCommand('default', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
     */
    public function testCommandDefault()
    {
        $object = new StringType($this->parent, 'string');

        $this->assertInstanceOf(StringType::class, $object);

        $object->handleCommand('default', 'word');

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'word',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
     */
    public function testCommandPattern()
    {
        $object = new StringType($this->parent, 'string');

        $this->assertInstanceOf(StringType::class, $object);

        $object->handleCommand('pattern', '[a-z]');

        $this->assertSame(array(
            'type' => 'string',
            'pattern' => '[a-z]',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
     */
    public function testCommandEnumWhenRange()
    {
        $object = new StringType($this->parent, 'string[0,]');

        $this->assertInstanceOf(StringType::class, $object);

        $this->expectException(SwaggerException::class, "Enumeration not allowed in ranged string: 'red green blue'");

        $object->handleCommand('enum', 'red green blue');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\StringType->handleCommand
     */
    public function testCommandEnum()
    {
        $object = new StringType($this->parent, 'string');

        $this->assertInstanceOf(StringType::class, $object);

        $object->handleCommand('enum', 'red green blue');

        $this->assertSame(array(
            'type' => 'string',
            'enum' => array('red', 'green', 'blue'),
        ), $object->toArray());
    }

    protected function setUp(): void
    {
        $this->parent = $this->getMockForAbstractClass(AbstractObject::class);
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf(AbstractObject::class, $this->parent);
    }

}
