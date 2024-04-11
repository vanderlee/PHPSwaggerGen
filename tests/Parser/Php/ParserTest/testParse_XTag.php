<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\api MyApi Example
 */
class testParse_XTag
{

    /**
     * @rest\endpoint /endpoint
     * @rest\x-something else
     * @rest\method GET Something
     */
    public function Dummy()
    {

    }

}
