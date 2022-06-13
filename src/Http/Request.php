<?php

namespace Http;

use Helpers\Helpers;

class Request
{
    use Helpers;
    // Stores the json content from an incoming request
    public $body = null;

    // Stores the json content from an incoming request
    public $getCurrentPathParams = null;

    public $params = null;
    public $files;
    public $headers = null;
    /**
     * 
     * Stores an incoming data
     */
    public $cookies = null;

    public $session = null;

    /**
     * 
     * bool $isSure Checks where the server connection is https or http
     */
    public $isSure = false;

    public function __construct()
    {
        try {
            $this->body = $this->getRequestBody();
            $this->getCurrentPathParams = $this->getParams();
            $this->headers = $this->getRequestHeaders();
            $this->cookies = $this->cookies();
            $this->session = $this->session();
            $this->isSure = $this->isSure();
            // $this->files = $this->files();
        } catch (\Throwable $e) {
            echo ($e->getMessage()) . " ";
        }

        // session_regenerate_id();
        // $this->resetCookie();
    }

    /**
     * 
     * @return object|bool
     * Serves as a getter for json content from an incoming request
     */


    private function getRequestBody(): object|null
    {
        $json = file_get_contents('php://input') ?? null;
        $object = json_decode($json);

        return ($object) ?? null;
    }


    private function getParams()
    {
        $urls = explode('/', $_SERVER['REQUEST_URI']);
        $params = array();
        for ($i = 0; $i < count($urls); $i++) {
            if (empty(trim($urls[$i]))) continue;
            $params[] = trim($urls[$i]);
        }
        return $params;
    }

    /**
     * 
     * @return  object|array|string|int
     * 
     * Get of the cookies from an incoming request
     * 
     */

    private function cookies(): object | array | string | int
    {
        $cookies = [];

        $key  = '';
        foreach ($_COOKIE as $key => $value) {

            // convert data to json object if the data is an array or object
            if (is_object(json_decode($value)) || is_array(json_decode($value))) {
                $cookies[$key] = json_decode($value);
            } else {
                // return a string if the data is not an array or object
                $cookies[$key] = $value;
            }
        }

        return $cookies ?? null;
    }

    /**
     * @return array|null
     *  $_POST data from an incoming request
     * 
     */
    public function postBody(): array|null
    {
        return $_POST ?? null;
    }

    /**
     * 
     * @return object
     * getRequestHeaders gets the request headers from a request
     * 
     */

    private function getRequestHeaders(): object
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') !== false) {
                $headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
            }
        }
        return Helpers::turnToJSON($headers);
    }

    /**
     * 
     * @return bool 
     * 
     * It returns the uri of the request
     * 
     */

    public function uri(): bool
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * 
     * Private method that returns the http status of the server
     * @return bool
     */

    private function isSure(): bool
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    /**
     * 
     * @param string|null $method 
     * If request $method is not provided, It returns the request method 
     * 
     * If request $method is provided, it checks if method matches
     * @return string|bool
     */

    public function method($method = null): string|bool
    {
        if (is_null($method) || !in_array(strtolower($method), ['post', 'get'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
        return strtolower($_SERVER['REQUEST_METHOD']) === strtolower($method);
    }
    private function files()
    {
        return isset($_FILES) ? Helpers::turnToJSON($_FILES) : null;
    }

    public function regenerate_session_id($bool = false)
    {
        session_regenerate_id($bool);
    }
    public function session()
    {
        if (!isset($_SESSION)) return null;
        return Helpers::turnToJSON($_SESSION);
    }
}