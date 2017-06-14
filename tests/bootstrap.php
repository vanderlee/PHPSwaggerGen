<?php

spl_autoload_register(function ($classname) {
	$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
	if (is_file($file)) {
		require_once $file;
	}
});

// backward compatibility
var_dump('\PHPUnit\Framework\TestCase', class_exists('\PHPUnit\Framework\TestCase'));
var_dump('\PHPUnit_Framework_TestCase', class_exists('\PHPUnit_Framework_TestCase'));

if (!class_exists('\PHPUnit\Framework\TestCase') && class_exists('\PHPUnit_Framework_TestCase')) {
	class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}