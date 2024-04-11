<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeClassMethod
{

    /**
     * @rest\endpoint /endpoint
     * @rest\method GET Something
     * @rest\see testParse_SeeClassMethod_Other::Method
     */
    public function Dummy()
    {

    }

}

class testParse_SeeClassMethod_Other
{

    /**
     * @rest\error 400
     */
    private function Method()
    {

    }

}
