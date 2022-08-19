<?php

namespace Routee\Http;

use Routee\Http\Response;
use Routee\View\View;
use Routee\Helpers\Helpers;
use Routee\Http\Request;

class Router
{
    private array $handlers;
    private $notFoundHandler;
    private $group = null;
    private const METHOD_POST = "POST";
    private const METHOD_GET = "GET";
    private const METHOD_PATCH = "PATCH";
    private const METHOD_PUT = "PUT";
    private const METHOD_DELETE = "DELETE";
    private $uploadSettings = [];
    private $routePath;
    private $setPath = null;
    private $middleware = [];

    public function useView(bool $bool)
    {
        if ($bool) {
            if (is_null($this->setPath)) {
                $this->setPath();
            }
            $this->setViewPath($this->setPath);
        }
    }
    public function get(string $path, $handler, $middleware = null)
    {
        $routePrefixPath = '';
        $middlewareStack = [];
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
            if (isset($this->group['middleware']) && is_array($this->group['middleware'])) {
                $middlewareStack = [...$middlewareStack, ...$this->group['middleware']];
            } else if (isset($this->group['middleware']) && is_string($this->group['middleware'])) {
                array_push($middlewareStack, $this->group['middleware']);
            }
        }

        if (isset($middleware['middleware']) && is_array($middleware['middleware'])) {
            $middlewareStack = [...$middlewareStack, ...$middleware['middleware']];
        } else if (isset($middleware['middleware']) && is_string($middleware['middleware'])) {
            array_push($middlewareStack, $middleware['middleware']);
        }

        $this->addHandlers(self::METHOD_GET,   $this->removeLastSlash($routePrefixPath . $path), $handler, $middlewareStack);
        return $this;
    }

    public function post(string $path, $handler, $middleware = null)
    {
        $routePrefixPath = '';
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
        }
        $middlewareStack = null;
        if (isset($middleware) && !is_null($middleware)) {
            $middlewareStack = $middleware;
            $this->addHandlers(self::METHOD_POST,   $this->removeLastSlash($routePrefixPath . $path), $handler, $middlewareStack['middleware']);
        } else
            $this->addHandlers(self::METHOD_POST,   $this->removeLastSlash($routePrefixPath . $path), $handler);
        return $this;
    }
    public function put(string $path, $handler, $middleware = null)
    {
        $routePrefixPath = '';
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
        }
        $middlewareStack = null;
        if (isset($middleware) && !is_null($middleware)) {
            $middlewareStack = $middleware;
            $this->addHandlers(self::METHOD_PUT,   $this->removeLastSlash($routePrefixPath . $path), $handler, $middlewareStack['middleware']);
        } else
            $this->addHandlers(self::METHOD_PUT,   $this->removeLastSlash($routePrefixPath . $path), $handler);
        return $this;
    }
    public function patch(string $path, $handler, $middleware = null)
    {
        $routePrefixPath = '';
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
        }
        $middlewareStack = null;
        if (isset($middleware) && !is_null($middleware)) {
            $middlewareStack = $middleware;
            $this->addHandlers(self::METHOD_PATCH,   $this->removeLastSlash($routePrefixPath . $path), $handler, $middlewareStack['middleware']);
        } else
            $this->addHandlers(self::METHOD_PATCH,   $this->removeLastSlash($routePrefixPath . $path), $handler);
        return $this;
    }
    public function delete(string $path, $handler, $middleware = null)
    {
        $routePrefixPath = '';
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
        }
        $middlewareStack = null;
        if (isset($middleware) && !is_null($middleware)) {
            $middlewareStack = $middleware;
            $this->addHandlers(self::METHOD_DELETE,   $this->removeLastSlash($routePrefixPath . $path), $handler, $middlewareStack['middleware']);
        } else
            $this->addHandlers(self::METHOD_DELETE,   $this->removeLastSlash($routePrefixPath . $path), $handler);
        return $this;
    }

    public function group($options = null, callable $callback = null)
    {
        $request = new Request;
        $response = new Response;

        // Implementing route routePrefixing
        if (isset($options['routePrefix'])) {
            $this->group['routePrefix'] = $options['routePrefix'];
        }
        if (isset($options['middleware'])) {
            $this->group['middleware'] = $options['middleware'];
        }

        $callback($request, $response, $this);
        $this->group = null;
    }
    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }
    private function addHandlers(string $method, string $path, $handler, $middleware = null): void
    {
        // $this->group = null;
        $this->handlers[$method . $path] = [
            'path' =>  $path,
            'handler' => $handler,
            'method' => $method,
            "middleware" => $middleware
        ];
    }

    function setViewPath($path)
    {
        $this->setPath = $path;
        $extracted = explode("/", $this->setPath);
        $newPath = $_SERVER['DOCUMENT_ROOT'] . "/{$extracted[count($extracted) - 1]}";
        if (file_exists($newPath)) {
            View::getPath($newPath);
        }
    }
    private function setPath()
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/views")) mkdir($_SERVER['DOCUMENT_ROOT'] . "/views");
        $this->setPath = $_SERVER['DOCUMENT_ROOT'] . "/views";
    }
    private function removeLastSlash($route)
    {
        // remove last slash from the route if length is greater than 1 
        if (strlen($route) > 1  && $route[strlen($route) - 1] == "/") return substr($route, 0, -1);
        return $route;
    }

    /**
     * 
     *@param array|object|null $array[]
     *
     * Array must be a key value pair with the following format
     * 
     * [
     * 
     * lifetime=>Lifetime of session in seconds
     * 
     * path=>The path to store the session
     * 
     * domain => The domain to use the session
     * 
     * secure=>Determines whether session should be secured or not
     * 
     * httpOnly=>determines whether the session should be httpOnly or not
     * 
     * ]
     * 
     */
    public function session_start(array|object|null $array = null)
    {

        if (is_array($array) && count($array) < 1) return;
        if (is_null($array)) return;

        if (array_values($array) === $array) {
            session_unset();
            session_destroy();
            throw new \Exception("session expects an associative value data, Indexed arrays provided");
        }

        if (is_object($array)) {
            session_set_cookie_params(
                $array->lifetime,
                $array->path,
                $array->domain,
                $array->secure,
                $array->httpOnly
            );
        }

        if (is_array($array)) {
            session_set_cookie_params(
                $array['lifetime'],
                $array['path'],
                $array["domain"],
                $array['secure'],
                $array['httpOnly']
            );
        }
        session_start();
    }



    // file handling 
    public function uploadSettings($data)
    {

        $GLOBALS['fileSettings'] = $data;
    }
    public function run()
    {

        $request = new Request();
        $response = new Response();
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $callback = null;

        $pa = $request->urlParams;


        foreach ($this->handlers as $handler) {

            $method = $_SERVER['REQUEST_METHOD'];
            $cutUrls = Helpers::arrangeArray(explode("/", $handler['path']));
            $path = Helpers::arrangeArray((array)$pa);

            if (count($path) !== count($cutUrls)) continue;

            // matches any route with the pattern :param|{param}|[param]
            if (preg_match_all("/{(.*?)}|(:\w+)|\[(.*?)\]/", $handler['path'], $matches)) {
                //    find and replace all :param with the value from the
                $param = null;
                $request->params = (object)[];
                $newUrl = [];
                $newFormedUrl = "";
                for ($i = 0; $i < count($cutUrls); $i++) {
                    // matches any route with the pattern :param|{param}|[param] that can be found in the $matches
                    if (preg_match("/{(.*?)}|(:\w+)|\[(.*?)\]/", $cutUrls[$i], $match)) {                        // Store params from query string
                        $param = preg_replace("/[{}]|[\[\]]|:/", "", $match[0]);


                        $indexedParam = array_search($match[0], $cutUrls);

                        $splittedUrl = explode("/", $handler['path']);
                        foreach ($splittedUrl as $splitted) {
                            if (!empty($splitted)) $newUrl[] = $splitted;
                        }
                        $newUrl[$indexedParam] = $path[$i];
                        $newFormedUrl = "/" . implode("/", $newUrl);
                        $handler['path'] = $newFormedUrl;

                        // reset newly formed url and newUrl
                        $newUrl = [];
                        $newFormedUrl = "";
                        if (!empty($param) && strlen($param) > 0 && $handler['path'] === $requestPath) {
                            $request->params->$param = $path[$i];
                        }
                    }
                }
            }
            if ($handler['path'] === $requestPath && $handler['method'] === $method) {
                $callback = $handler['handler'];
                // // run middleware here
                $middlewareRun = [];
                if (isset($handler['middleware'])) {
                    $middlewareRun = $handler['middleware'];
                }
                foreach ($middlewareRun as $middleware) {
                    $m =  new $middleware;
                    $m->run($request, $response);
                }
                break;
            }
        }


        // Call middleware here

        // Add router controller using ClassName@method
        // if (is_string($callback)) {
        //     $exploded = explode("@", $callback);
        //     $className = new $exploded[0];
        //     $callback = [$className, $exploded[1]];
        // }

        // Add a route controller using an array [ClassName::class, method]
        if (is_array($callback)) {
            if (array_values($callback) === $callback) {
                $className = new $callback[0];
                $callback = [$className, $callback[1]];
            } else {
                /*Add a route controller using an array [
                    controller=>ClassName::class,
                    action=>method
                ]
                */
                $className = new $callback['controller'];
                $callback = [$className, $callback['action']];
            }
        }
        if (!$callback) {
            http_response_code(404);
            if (!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }
        call_user_func_array($callback, [
            $request, $response
        ]);
        $request->params = (object)[];
    }
}