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
class testParse_WithMethod
{
	/**
	 * Description of method
	 * @rest\description some description
	 */
	public function Method1()
	{
		// @rest\method get something
	}
}
