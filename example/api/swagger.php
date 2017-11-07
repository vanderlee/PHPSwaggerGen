<?php

	require_once __DIR__ . '/autoloader.php';

	$files = array('Example.class.php');

	$SwaggerGen = new \SwaggerGen\SwaggerGen($_SERVER['HTTP_HOST'], dirname($_SERVER['REQUEST_URI']));
	//$SwaggerGen->define('admin');
	//$SwaggerGen->define('root');
	
	header('Content-type: application/json');
	echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);

	//header('Content-type: application/x-yaml');
	//echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_YAML);
