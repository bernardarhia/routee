<?php

namespace Routee\controller;

use Routee\Request;
use Routee\Response;
use Routee\View\View;

class StudentController
{

    public function index(Request $request, Response $response)
    {
        $request->regenerate_session_id(true);
        View::render("create", ["session" => $request->session, "data" => "A very nice data"]);
    }
    public function create(Request $request, Response $response)
    {
        return $response->session(['id' => 1, "name" => "ben"])->send($request->session->name);
    }
}