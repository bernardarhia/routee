<?php

error_reporting(E_ALL);
ini_set('display_error', 1);

use Routee\Http\Request;
use Routee\Http\Response;
use Routee\Http\Router;

require_once __DIR__ . "/vendor/autoload.php";
$router = new Router;

$router->session_start([]);

$router->uploadSettings([
    "limits" => [
        "name" => 100000000,
        "file" => 2000000,
    ],
    "allowedExtension" => [
        // "name" => [
        //     "pic/png" => "png",
        //     "pic/jpeg" => "jpg",
        //     "pic/gif" => "gif",
        // ],
        "destination" => [
            "name" => "uploads",
        ]
    ]
]);

$router->post('/upload', function ($request, Response $response) {
    $response->send($request->files);
    // $response->send($request->saveFile(['validate' => true]));
    // $request->saveFile();
});
$router->run();