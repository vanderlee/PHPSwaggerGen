<?php

class Base_PHPUnit7 extends PHPUnit\Framework\TestCase
{
	public function expectException(string $exception, string $message = ''): void
	{
		parent::expectException($exception);
		if ($message !== '') {
			parent::expectExceptionMessage($message);
		}
	}
}
