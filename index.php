<?php
// enable all php error

use Http\Router;


error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/vendor/autoload.php";


$router = new Router;
// ROUTES
include_once __DIR__ . "/test/student.php";

$session_data = [
    "lifetime" => 3600,
    "path" => "/",
    "domain" => "localhost",
    "secure" => false,
    "httpOnly" => true
];
$router->session_start($session_data);
// $router->static("");
// 404 ROUTE
$router->addNotFoundHandler(function () {
    echo "Not found";
});

$router->run();