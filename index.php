<?php
// enable all php error
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Routee\Http\Router;
use Routee\View\View;


require_once __DIR__ . "/vendor/autoload.php";

$router = new Router;

$router->get(
    "/:id",
    function () {
        View::render("create");
    }
);
$router->post("/session", function ($request, $response) {
    $response->session(['isLogged' => true, 'isAdmin' => 'admin'])->send("session set");
});

$router->addNotFoundHandler(function ($request, $response) {
    $response->send("404");
});
$router->run();


//$string  =  preg_replace('/[^A-Za-z0-9\-]/', '-',$slug_String);
//$final_string = preg_replace('/-+/','-',$string);