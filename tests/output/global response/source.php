<?php

namespace Test\Parser\Php\ParserTest;

/**
 * Demonstrates a very simple but complete API
 * @rest\title Minimal
 * @rest\api MyApi Example
 * @rest\path long[0,> listid
 */
class Example
{

    /**
     * @rest\endpoint /endpoint/{listid}
     * @rest\method GET Something
     * @rest\param listid
     * @rest\response 200
     */
    public function Dummy()
    {

    }

}
