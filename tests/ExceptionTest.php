<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{

    /**
     * @covers \SwaggerGen\Exception::__construct
     */
    public function testConstructor0()
    {
        $this->expectException('\SwaggerGen\Exception');

        throw new \SwaggerGen\Exception('', 0);
    }

    /**
     * @covers \SwaggerGen\Exception::__construct
     */
    public function testConstructor1()
    {
        $this->expectException('\SwaggerGen\Exception', 'This is a message');

        throw new \SwaggerGen\Exception('This is a message', 0);
    }

    /**
     * @covers \SwaggerGen\Exception::__construct
     */
    public function testConstructor2()
    {
        $this->expectException('\SwaggerGen\Exception', 'This is a message', 1234);

        throw new \SwaggerGen\Exception('This is a message', 1234);
    }

}
