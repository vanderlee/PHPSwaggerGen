<?php

class DateTypeTest extends SwaggerGen_TestCase
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
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructNotADate()
    {
        $this->expectException('\SwaggerGen\Exception', "Not a date: 'wrong'");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'wrong');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructRange()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable date definition: 'date[1,]'");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date[1,]');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructParentheses()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable date definition: 'date()'");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date()');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDate()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'date',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateTime()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'datetime');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'date-time',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateDefaultEmpty()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable date definition: 'date='");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date=');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateDefaultBlank()
    {
        $this->expectException('\SwaggerGen\Exception', "Unparseable date definition: 'date= '");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date= ');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateDefaultWrong()
    {
        $this->expectException('\SwaggerGen\Exception', "Invalid 'date' default: 'wrong'");

        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date=wrong');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateDefault()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date=1999-12-31');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'date',
            'default' => '1999-12-31',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType::__construct
     */
    public function testConstructDateTimeDefault()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'datetime=1999-12-31T23:59:59+11:00');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'date-time',
            'default' => '1999-12-31T23:59:59+11:00',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType->handleCommand
     */
    public function testCommandDefaultNoValue()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->expectException('\SwaggerGen\Exception', "Empty date default");
        $object->handleCommand('default', '');
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType->handleCommand
     */
    public function testCommandDefaultWrong()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $this->expectException('\SwaggerGen\Exception', "Invalid 'date' default: 'wrong'");
        $object->handleCommand('default', 'wrong');

        $this->assertSame(array(
            'type' => 'string',
            'default' => 'date',
        ), $object->toArray());
    }

    /**
     * @covers \SwaggerGen\Swagger\Type\DateType->handleCommand
     */
    public function testCommandDefault()
    {
        $object = new SwaggerGen\Swagger\Type\DateType($this->parent, 'date');

        $this->assertInstanceOf('\SwaggerGen\Swagger\Type\DateType', $object);

        $object->handleCommand('default', '1999-12-31');

        $this->assertSame(array(
            'type' => 'string',
            'format' => 'date',
            'default' => '1999-12-31',
        ), $object->toArray());
    }

}
