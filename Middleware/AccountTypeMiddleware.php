<?php

namespace Middleware;

use Routee\Http\Request;
use Routee\Http\Response;

class AccountTypeMiddleware
{

    public function auth(Request $request = null, Response $response)
    {
        if (!$request->session) {
            return $response->statusCode(400)->json(["message" => "Invalid account type"]);
        }
    }
}