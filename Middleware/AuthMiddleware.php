<?php

namespace Middleware;

use Routee\Http\Request;
use Routee\Http\Response;

class AuthMiddleware
{

    public function auth(Request $request = null, Response $response)
    {
        if (!$request->session->isAuth) {
            return $response->json(["message" => "You are not authorized"]);
            die;
        }
    }
}