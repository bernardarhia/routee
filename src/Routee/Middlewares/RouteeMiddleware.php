<?php

namespace Routee\Middlewares;

use Routee\Http\Request;
use Routee\Http\Response;
use Routee\Http\Router;

class RouteeMiddleware
{
    protected $middleware;
    protected $stack = [];
    protected $router;
    protected $request;
    protected $response;
    protected $next;

    public function __construct(Router $router, Request $request, Response $response)
    {
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;
    }

    public function add(string $middleware)
    {
        $this->stack[] = $middleware;
    }
    public function run()
    {
        $this->next = function () {
            $this->router->run();
        };
        $this->next();
    }
    public function next()
    {
        $middleware = array_shift($this->stack);
        if (!$middleware) {
            return $this->next();
        }
        $this->middleware = new $middleware($this->request, $this->next);
        $this->middleware->run();
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}