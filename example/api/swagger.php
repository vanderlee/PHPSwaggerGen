<?php

	require_once __DIR__ . '/autoloader.php';

	$files		= ['
			description SwaggerGen 2 Example API
			title Example API
		',
		__DIR__ . '/Example.class.php',
	];
	$classdirs	= [];

	$Generator = new \SwaggerGen\SwaggerGen($_SERVER['HTTP_HOST'],  dirname($_SERVER['REQUEST_URI']));
	//$SwaggerGen->define('admin');
	$array = $Generator->getSwagger($files, $classdirs);
	
	header('Content-type: application/json');
	echo json_encode($array, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);	
