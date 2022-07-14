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

    public function useView(bool $bool)
    {
        if ($bool) {
            if (is_null($this->setPath)) {
                $this->setPath();
            }
            $this->setViewPath($this->setPath);
        }
    }
    public function get(string $path, $handler)
    {
        $this->addHandlers(self::METHOD_GET, $this->routePath ? $this->routePath . $path : $path, $handler);

        return $this;
    }

    public function post(string $path, $handler)
    {
        $this->addHandlers(self::METHOD_POST, $this->routePath ? $this->routePath . $path : $path, $handler);
        return $this;
    }
    public function put(string $path, $handler)
    {
        $this->addHandlers(self::METHOD_PUT, $this->routePath ? $this->routePath . $path : $path, $handler);

        return $this;
    }
    public function patch(string $path, $handler)
    {
        $this->addHandlers(self::METHOD_PATCH, $this->routePath ? $this->routePath . $path : $path, $handler);

        return $this;
    }
    public function delete(string $path, $handler)
    {
        $this->addHandlers(self::METHOD_DELETE, $this->routePath ? $this->routePath . $path : $path, $handler);

        return $this;
    }

    public function group($options = null, $callback)
    {
        $request = new Request;
        $response = new Response;

        // Implementing route routePrefixing
        if (isset($options['routePrefix'])) {
            $this->group['routePrefix'] = $options['routePrefix'];
        }


        $callback($request, $response, $this);
        $this->group = null;
    }
    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }
    private function addHandlers(string $method, string $path, $handler): void
    {

        $routePrefixPath = '';
        if ($this->group && is_array($this->group) && count($this->group) > 0) {
            $routePrefixPath = $this->group['routePrefix'];
        }

        $this->handlers[$method . $path] = [
            'path' => $routePrefixPath . $path,
            'handler' => $handler,
            'method' => $method
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
        // $this->uploadSettings = $data;
        $GLOBALS['fileSettings'] = $data;
    }


    public function run()
    {
        $request = new Request();
        $response = new Response();
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $callback = null;
        foreach ($this->handlers as $handler) {
            $method = $_SERVER['REQUEST_METHOD'];
            $cutUrls = Helpers::arrangeArray(explode("/", $handler['path']));
            $path = Helpers::arrangeArray($request->params);

            if (count($path) !== count($cutUrls)) continue;

            // matches any route with the pattern :param|{param}|[param]

            if (preg_match_all("/{(.*?)}|(:\w+)|\[(.*?)\]/", $handler['path'], $matches)) {
                //    find and replace all :param with the value from the
                // print_r($matches);
                $request->params = (object)[];
                for ($i = 0; $i < count($cutUrls); $i++) {
                    // matches any route with the pattern :param|{param}|[param] that can be found in the $matches
                    if (preg_match("/{(.*?)}|(:\w+)|\[(.*?)\]/", $cutUrls[$i], $match)) {
                        // Store params from query string
                        // $param = substr($match[0], 1);
                        // Replace any {} or : or [] in param
                        $param = preg_replace("/[{}]|[\[\]]|:/", "", $match[0]);

                        $request->params->$param = $path[$i];
                    }
                    $handler['path'] = str_replace(($cutUrls[$i]), ($path[$i]), $handler['path']);
                }
            }
            if ($handler['path'] === $requestPath && $handler['method'] === $method) {
                $callback = $handler['handler'];
            }
        }

        // Add router controller using ClassName@method
        if (is_string($callback)) {
            $callback = explode("@", $callback);
            $callback[0] = new $callback[0];
            $callback[1] = $callback[1];
        }

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
    }
}