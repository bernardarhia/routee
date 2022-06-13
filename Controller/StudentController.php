<?php

use Http\Request as HttpRequest;
use Http\Response as HttpResponse;
use View\View;

class StudentController
{
    public function index(HttpRequest $request, HttpResponse $response)
    {
        $request->regenerate_session_id(true);
        View::render("create", ["session" => $request->session, "data" => "A very nice data"]);
    }
    public function create(HttpRequest $request, HttpResponse $response)
    {
        return $response->session(['id' => 1, "name" => "ben"])->send($request->session->name);
    }
    public function delete(HttpRequest $request, HttpResponse $response)
    {
        return $response->session(['id' => 1, "name" => "ben"])->send($request->session->name);
    }
    public function update(HttpRequest $request, HttpResponse $response)
    {
        return $response->session(['id' => 1, "name" => "ben"])->send($request->session->name);
    }
}