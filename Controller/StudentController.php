<?php

use Routee\Http\Request;
use Routee\Http\Response;
use Routee\View\View;

class StudentController
{
    public function index(Request $request, Response $response)
    {
        View::render("create");
    }
    public function create(Request $request, Response $response)
    {
        return $response->session(['id' => 1, "isAuth" => true])->send("session set");
    }
}