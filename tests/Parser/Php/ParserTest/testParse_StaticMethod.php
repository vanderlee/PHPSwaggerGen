<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_StaticMethod
{

    /**
     * @rest\endpoint /endpoint
     * @rest\method GET Something
     * @rest\see static::Method
     */
    public function Dummy()
    {

    }

    /**
     * @rest\error 400
     */
    private function Method()
    {

    }

}
