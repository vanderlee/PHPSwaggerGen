<?php

class Base_PHPUnit6 extends PHPUnit\Framework\TestCase
{
	/**
	 * @param string $exception
	 * @param string $message
	 * @return void
	 */
	public function expectException($exception, $message = '')
	{
		parent::expectException($exception);
		if ($message !== '') {
			parent::expectExceptionMessage($message);
		}
	}
}
