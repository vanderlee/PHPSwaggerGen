<?php

	spl_autoload_register(function ($classname) {
		$file = dirname(__DIR__) . '/' . $classname . '.php';
		if (is_file($file)) {
		var_dump('load', $file);
			include_once $file;
		}
	});