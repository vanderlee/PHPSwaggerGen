<?php

spl_autoload_register(function($classname) {
	$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
	if (is_file($file)) {
		require_once $file;
	}
});


// Unify setting expected exception for different phpunit versions

// starting with PHPUnit 7.0 `expectException()` needs parameter type-hint
// it has to be in a separate file to prevent older php version from choking on scalar parameter type hints
// before PHPUnit 6.0 there was `setExpectedException()`
// inbetween there was `expectException()` without type hint

if (class_exists('PHPUnit\Runner\Version')) {
	$version = PHPUnit\Runner\Version::id();
} else if (class_exists('PHPUnit_Runner_Version')) {
	$version = PHPUnit_Runner_Version::id();
} else {
	throw new Exception('Cannot detect PHPUnit version');
}

if (version_compare($version, '7.0.0', '>=')) {
	require dirname(__FILE__) . '/Base/PHPUnit7.php';
	class_alias('Base_PHPUnit7', 'SwaggerGen_TestCase');
} else if (version_compare($version, '6.0.0', '>=')) {
	require dirname(__FILE__) . '/Base/PHPUnit6.php';
	class_alias('Base_PHPUnit6', 'SwaggerGen_TestCase');
} else {
	require dirname(__FILE__) . '/Base/PHPUnit5.php';
	class_alias('Base_PHPUnit5', 'SwaggerGen_TestCase');
}
