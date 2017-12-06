<?php

	require_once __DIR__ . '/autoloader.php';

	$files = array('Example.class.php');

	$SwaggerGen = new \SwaggerGen\SwaggerGen($_SERVER['HTTP_HOST'], dirname($_SERVER['REQUEST_URI']));	
	
	require_once 'CustomType/Ipv4Type.php';
	
	//$SwaggerGen->define('admin');
	//$SwaggerGen->define('root');
	
	header('Content-type: application/json');
	echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);
	
	// IpType registers itself!

	//header('Content-type: application/x-yaml');
	//echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_YAML);
