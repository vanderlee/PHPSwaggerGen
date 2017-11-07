<?php

spl_autoload_register(function ($classname) {
	$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
	if (is_file($file)) {
		require_once $file;
	}
});

// backward compatibility
if (!class_exists('PHPUnit\Framework\TestCase') && class_exists('PHPUnit_Framework_TestCase')) {
	class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

class SwaggerGen_TestCase extends PHPUnit\Framework\TestCase
{

	public function expectException($exception, $message = '')
	{
		// while setExpectedException accepts message to assert on, expectException does not
		if (method_exists($this, 'setExpectedException')) {
			$ret = $this->setExpectedException($exception, $message);
		} else {
			$ret = parent::expectException($exception);
			if ($message !== '') {
				parent::expectExceptionMessage($message);
			}
		}
		return $ret;
	}

}
