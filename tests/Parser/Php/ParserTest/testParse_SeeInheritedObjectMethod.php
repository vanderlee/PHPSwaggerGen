<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_SeeInheritedThisMethod
{

    /**
     * @rest\endpoint /endpoint
     * @rest\method GET Something
     * @rest\see testParse_SeeInheritedThisMethod_Other->Method
     */
    public function Dummy()
    {

    }

}

class testParse_SeeInheritedThisMethod_Other_Super
{

    /**
     * @rest\error 400
     */
    private function Method()
    {

    }

}

class testParse_SeeInheritedThisMethod_Other extends testParse_SeeInheritedThisMethod_Other_Super
{

}
