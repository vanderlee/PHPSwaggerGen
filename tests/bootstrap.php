<?php

if (!class_exists('SwaggerGen_TestCase', false)) {
    class_alias('PHPUnit\\Framework\\TestCase', 'SwaggerGen_TestCase');
}

spl_autoload_register(function ($classname) {
    $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
