<?php

namespace Http;

use Helpers\Helpers;
use Http\Request as HttpRequest;
use Http\Response as HttpResponse;

use View\View;

class Router
{
    private array $handlers;
    private $notFoundHandler;
    private const METHOD_POST = "POST";
    private const METHOD_GET = "GET";
    private $routePath;

    private $setPath = null;

    public function __construct()
    {
        if (is_null($this->setPath)) {
            $this->setPath();
        }
        $this->setViewPath($this->setPath);
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

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }
    private function addHandlers(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'handler' => $handler,
            'method' => $method
        ];
    }

    public function run()
    {
        $request = new HttpRequest();
        $response = new HttpResponse();
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $callback = null;

        foreach ($this->handlers as $handler) {
            $method = $_SERVER['REQUEST_METHOD'];
            $cutUrls = Helpers::arrangeArray((explode("/", $handler['path'])));
            $path = Helpers::arrangeArray(($request->getCurrentPathParams));

            if (count($path) !== count($cutUrls)) continue;
            if (preg_match_all("/:\w+/i", $handler['path'], $matches)) {

                print_r($matches);
                return;
                //    find and replace all :param with the value from the
                $request->params = [];
                for ($i = 0; $i < count($cutUrls); $i++) {
                    if (preg_match("/:\w+/i", $cutUrls[$i], $match)) {
                        // Store params from query string
                        $param = substr($match[0], 1);
                        $request->params[$param] = $path[$i];
                    }
                    $handler['path'] = str_replace(($cutUrls[$i]), ($path[$i]), $handler['path']);
                }
            }
            if ($handler['path'] === $requestPath && $handler['method'] === $method) {
                $callback = $handler['handler'];
            }
        }


        if (is_array($callback)) {
            $className = new $callback[0];
            $callback = [$className, $callback[1]];
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

    function setViewPath($path)
    {
        $this->setPath = $path;
        $extracted = explode("/", $this->setPath);
        $newPath = __DIR__ . "/../../{$extracted[count($extracted) - 1]}";
        if (file_exists($newPath)) View::getPath($newPath);
    }
    private function setPath()
    {
        if (!file_exists(__DIR__ . "/../../views")) mkdir(__DIR__ . "/../../views");
        $this->setPath = __DIR__ . "/../../views";
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
}