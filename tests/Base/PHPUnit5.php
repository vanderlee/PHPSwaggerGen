<?php

class Base_PHPUnit5 extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $exception
     * @param string $message
     * @return void
     */
    public function expectException($exception, $message = '')
    {
        parent::setExpectedException($exception, $message);
    }
}
