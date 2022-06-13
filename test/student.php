<?php

use Routee\controller\StudentController;

$router->get("/student/create", [StudentController::class, "index"]);
$router->post("/student/create", [StudentController::class, "create"]);