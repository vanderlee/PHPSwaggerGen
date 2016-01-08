<?php

	require_once __DIR__ . '/autoloader.php';

	$files		= array('Example.class.php');

	$SwaggerGen = new \SwaggerGen\SwaggerGen($_SERVER['HTTP_HOST'],  dirname($_SERVER['REQUEST_URI']));
	//$SwaggerGen->define('admin');
	//$SwaggerGen->define('root');
	$array = $SwaggerGen->getSwagger($files);
	
	header('Content-type: application/json');
	echo json_encode($array, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);	
