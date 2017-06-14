<?php

spl_autoload_register(function ($classname) {
	$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
	if (is_file($file)) {
		require_once $file;
	}
});