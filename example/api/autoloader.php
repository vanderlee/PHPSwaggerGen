<?php

spl_autoload_register(function (string $classname) {
    $classPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $classname);
    $file = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $classPath . '.php';
    if (is_file($file)) {
        include_once $file;
    }
});