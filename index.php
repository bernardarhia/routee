<?php
// enable all php error
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Routee\Http\Router;
use Routee\View\View;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router;

$router->session_start(
    [
        "lifetime" => 60 * 60 * 24 * 30,
        "path" => "/",
        "domain" => "localhost",
        "secure" => false,
        "httpOnly" => true
    ]
);
include_once __DIR__ . "/routes/student.php";
$router->get(
    "/",
    function () {
        View::render("create");
    }
);
$router->run();