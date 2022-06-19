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
        "file" => ["pic/png", "pic/jpeg", "pic/gif"],
        "name" => ["pic/png", "pic/jpeg", "pic/gif"],
    ],
    "destination" => [
        "file" => "uploads",
        "name" => "files"
    ],
    "renameFiles" => [
        "name" => true,
        "file" => false,
    ]
]);

$router->post('/upload', function (Request $request, Response $response) {
    if (!$request->files) die($response->statusCode(400)->json(["error" => "No files uploaded"]));
    $files = $request->files;
    $file = $files->file;
    $result = $request->saveFile();
    $response->send($result);
});
$router->run();