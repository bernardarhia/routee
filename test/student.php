<?php

include_once __DIR__ . "/../Controller/StudentController.php";
$router->get("/student/create/:id/:sid", [StudentController::class, "index"])
    ->post("/student/create", [StudentController::class, "create"])
    ->delete("/student/delete", [StudentController::class, "delete"])
    ->patch("/student/update", [StudentController::class, "update"]);
