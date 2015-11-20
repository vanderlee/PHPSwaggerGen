<?php

	spl_autoload_register(function ($classname) {
		$file = dirname(__DIR__) . '/' . $classname . '.php';
		var_dump($file);
		if (is_file($file)) {
			include_once $file;
		}
	});