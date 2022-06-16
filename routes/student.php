<?php

// use Middleware\AuthMiddleware;

use Middleware\AccountTypeMiddleware;
use Middleware\AuthMiddleware;

include_once __DIR__ . "/../Controller/StudentController.php";
include_once __DIR__ . "/../Middleware/AuthMiddleware.php";
include_once __DIR__ . "/../Middleware/AccountTypeMiddleware.php";

$groupMiddleware = [AuthMiddleware::class => "auth", AccountTypeMiddleware::class => "auth"];
// $groupMiddleware = [];

$router->get(
    "/student",
    [StudentController::class, "index"]
);
$router->post("/student/create", [StudentController::class, "create"], $groupMiddleware);