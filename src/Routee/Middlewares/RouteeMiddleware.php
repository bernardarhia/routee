<?php

use Routee\Http\Request;
use Routee\Http\Router;

class RouteeMiddleware
{
    private $middleware;
    private $stack = [];
    private $router;
    private $request;
    private $next;


    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;
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