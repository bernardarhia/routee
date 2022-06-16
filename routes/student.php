<?php

// use Middleware\AuthMiddleware;

use Middleware\AuthMiddleware;

include_once __DIR__ . "/../Controller/StudentController.php";
include_once __DIR__ . "/../Middleware/AuthMiddleware.php";

$groupMiddleware = [AuthMiddleware::class => "auth"];


$router->get(
    "/student",
    [StudentController::class, "index"],
    $groupMiddleware
)
    ->post("/student/create", [StudentController::class, "create"], $groupMiddleware);