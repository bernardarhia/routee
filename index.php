<?php
// enable all php error

use Http\Router;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router;

$router->get("/", function () {
    echo "Hello World";
});
$router->run();