<?php
include_once 'vendor/autoload.php';
include_once 'App/iran.php';

const JWT_KEY = "iranCitiesfojhxg";
const JWT_ALG = "HS256";
spl_autoload_register(function ($class){
    $class_file = __DIR__ . "/" . $class . ".php";
    if(!(file_exists($class_file) and is_readable($class_file)))
        die("$class not found");
    include_once $class_file;
});
