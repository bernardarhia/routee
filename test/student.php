<?php

include_once __DIR__ . "/../Controller/StudentController.php";
$router->get("/student/create", [StudentController::class, "index"]);
$router->post("/student/create", [StudentController::class, "create"]);