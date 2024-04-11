<?php

use SwaggerGen\Exception as SwaggerException;
use SwaggerGen\Swagger\Type\Custom\Ipv4Type;
use SwaggerGen\SwaggerGen;
use SwaggerGen\TypeRegistry;

require_once __DIR__ . '/autoloader.php';

$files = ['Example.class.php'];

$TypeRegistry = new TypeRegistry();
$TypeRegistry->add(Ipv4Type::class);

$SwaggerGen = new SwaggerGen($_SERVER['HTTP_HOST'], dirname($_SERVER['REQUEST_URI']));
$SwaggerGen->setTypeRegistry($TypeRegistry);
try {
    $json = $SwaggerGen->getSwagger($files, [], SwaggerGen::FORMAT_JSON_PRETTY);
} catch (SwaggerException $e) {
    var_dump($e);
}

header('Content-type: application/json');
echo $json;
