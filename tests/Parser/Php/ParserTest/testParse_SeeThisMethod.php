<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeThisMethod
{

    /**
     * @rest\endpoint /endpoint
     * @rest\method GET Something
     * @rest\see $this->Method
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
