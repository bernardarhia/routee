<?php

use Routee\Http\Request;
use Routee\Http\Response;
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
    public function delete(Request $request, Response $response)
    {
        return $response->session(['id' => 1, "name" => "ben"])->send($request->session->name);
    }
    public function update(Request $request, Response $response)
    {
        $response->cookie("data", 5, time() + 60 * 60 * 24 * 30, "/", "localhost", false, true);
        return $response->session(['id' => 1, "name" => "ben"])->send($request->cookies);
    }
}