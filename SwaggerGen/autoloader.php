<?php

	spl_autoload_register(function ($class) {
		$file = dirname(__FILE__). '/' . $class . '.php';
		if (is_file($file)) {
			include_once $file;
		}
	});