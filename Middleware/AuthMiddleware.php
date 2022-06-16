<?php

namespace Middleware;

use Routee\Http\Request;
use Routee\Http\Response;

class AuthMiddleware
{

    public function auth(Request $request = null, Response $response)
    {
        if (!$request->session || !$request->session->isAuth) {
            return $response->statusCode(400)->json(["message" => "you not authenticated"]);
        }
    }
}