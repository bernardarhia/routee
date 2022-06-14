<?php

namespace Interfaces;

use Routee\Http\Request;
use Routee\Http\Response;

interface Middleware
{
    public function __construct(Request $request, Response $response);
}