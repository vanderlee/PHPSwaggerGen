<?php

namespace Test\Parser\Php\ParserTest;

/*
 * General comment
 * @rest\title Some words
 */

/**
 * Description of class
 * @rest\version 2
 */
class testParse_MethodNonDoc
{
	// @rest\method get something
	public function Method1() {

	}
}
