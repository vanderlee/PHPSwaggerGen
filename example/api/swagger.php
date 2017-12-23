<?php

	require_once __DIR__ . '/autoloader.php';

	$files = array('Example.class.php');
	
	$TypeRegistry = new \SwaggerGen\TypeRegistry();
//	\SwaggerGen\Swagger\Type\Custom\Ipv4Type::setFormats(array('ipv4'));
	$TypeRegistry->add('\SwaggerGen\Swagger\Type\Custom\Ipv4Type');

	$SwaggerGen = new \SwaggerGen\SwaggerGen($_SERVER['HTTP_HOST'], dirname($_SERVER['REQUEST_URI']), array(), $TypeRegistry);	
	
	// @todo Allow explicitly format name specification for conflict resolution.
	// @todo Automatically scan the default types (how to register multiple type names; e.g. StringType's names)
	// @todo Put TypeFactory method and the registry into a single class for easy management (how about Parameter?)
	// @todo Allow specification of Property-only or Property-and-parameter (only for certain types. Use static methods on type?)
	
	//$SwaggerGen->define('admin');
	//$SwaggerGen->define('root');
	
	header('Content-type: application/json');
	echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);
	
	// IpType registers itself!

	//header('Content-type: application/x-yaml');
	//echo $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_YAML);
