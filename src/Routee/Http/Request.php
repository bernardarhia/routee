<?php

namespace Routee\Http;

use Routee\Helpers\Helpers;
use Routee\Validate\Validator;

class Request
{

    use Helpers;
    // use FileHelpers;
    // Stores the json content from an incoming request
    public $body = null;
    public $fileSettings = null;

    // Stores the json content from an incoming request
    public $params = null;
    public $urlParams = null;
    public $files = null;
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
        $this->urlParams = $this->getParams();
        $this->body = $this->getRequestBody();
        $this->headers = $this->getRequestHeaders();
        $this->cookies = $this->cookies();
        $this->session = $this->session();
        $this->isSure = $this->isSure();
        $this->files = $this->files();
    }

    /**
     * 
     * @return object|bool
     * Serves as a getter for json content from an incoming request
     */


    private function getRequestBody(): object|null
    {
        $body = null;
        if (isset($_POST) && count($_POST) > 0) {
            $body = $_POST;
        } else {
            $body = json_decode(file_get_contents('php://input'), true) ?? null;
        }

        $object = Helpers::turnToJSON($body);

        // loop through the object and secure the inputs
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $object->$key = $value;
            }
        }
        return ($object) ?? null;
    }

    function secureInput($value)
    {
        // if (is_array($value)) {
        //     foreach ($value as $key => $value) {
        //         if (!empty($value) && is_string($value)) $value[$key] = $this->secureInput(strip_tags($value));
        //     }
        // }
    }

    private function getParams(): object
    {
        $urls = explode('/', $_SERVER['REQUEST_URI']);
        $params = [];
        foreach ($urls as $key => $value) {
            if (empty(trim($value))) continue;
            array_push($params, trim($value));
        }
        return (object) $params;
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

        if (isset($_COOKIE['PHPSESSID']) && count($_COOKIE) < 2) return $cookies;
        $key  = '';
        foreach ($_COOKIE as $key => $value) {

            if ($key == "PHPSESSID") continue;
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
    public function setUploadSettings($data)
    {
        $this->fileSettings = $data;
    }
    private function files()
    {

        $this->fileSettings = $GLOBALS['fileSettings'] ?? null;
        $files = [];
        $limit = null;
        if (isset($_FILES) && count($_FILES) > 0) {
            foreach ($_FILES as $key => $value) {
                if (!is_null($this->fileSettings) && count($this->fileSettings) > 0 && array_values($this->fileSettings) != $this->fileSettings) {
                    foreach ($this->fileSettings as $fileKey => $fileValue) {
                        // append extra file data to the file
                        $limit =  $this->fileSettings['limits'][$key] ?? null;
                        $allowedExtension =  $this->fileSettings['allowedExtension'][$key] ?? null;
                        $destination =  $this->fileSettings['destination'][$key] ?? null;
                        $renameFiles =  $this->fileSettings['renameFiles'][$key] ?? null;

                        $value['limit'] = $limit;
                        $value['allowedExtension'] = $allowedExtension;
                        $value['destination'] = $destination;
                        $value['renameFiles'] = $renameFiles;
                    }
                }

                $data = new \SplFileInfo($value['name']);
                $value['extension'] = $data->getExtension();
                $value['newName'] = isset($value['renameFiles']) && $value['renameFiles'] ? Helpers::renameFiles($value['extension']) : null;
                $value['mimetype'] = ($value['type']);
                $value['formattedSize'] = Helpers::sizeFilter($value['size']);
                $value['formattedLimitSize'] = Helpers::sizeFilter($limit);
                $files[$key] = $value;
                unset($value['type']);
            }
            unset($GLOBALS['fileSettings']);
            return Helpers::turnToJSON($files) ?? null;
        }
    }


    public function regenerate_session_id($bool = false)
    {
        session_regenerate_id($bool);
    }

    public function session(): object|null
    {
        if (!isset($_SESSION)) return null;
        return Helpers::turnToJSON($_SESSION) ?? null;
    }

    public function redirect($path = null)
    {
        header("Location: " . $path);
        die;
    }
    public function saveFile($fileIndex = null)
    {
        $response = [
            "error" => false,
            "message" => null,
            "data" => []
        ];
        if (is_null($fileIndex)) {
            $files = $this->files;
            if ($files) {
                foreach ($files as $key => $value) {
                    if (!$value->destination) {
                        $response['error'] = true;
                        $response['message'] = "Destination not set";
                    } else {
                        if (!is_dir($value->destination)) {
                            mkdir($value->destination, 0777, true);
                        }
                        // die(json_encode([$key, $value]));
                        $fileName =  !$value->newName || empty($value->newName) ? $value->name : $value->newName;
                    }
                    if ($value->allowedExtension && is_array($value->allowedExtension)) {
                        if (!in_array($value->mimetype, $value->allowedExtension)) {
                            $response['error'] = true;
                            $response['message'] =  "Invalid file extension ." . $value->mimetype;
                        }
                    }
                    if ($value->size > $value->limit) {
                        $response['error'] = true;
                        $response['message'] =  "File is too large. maximum upload size should be " . $value->formattedLimitSize;
                    }
                    if (!$response['error']) {
                        move_uploaded_file($value->tmp_name, $value->destination . "/" . $fileName);
                        $response['data'][$key] = $value;
                        $response['message'] = "File uploaded successfully";
                        unset($value->tmp_name);
                    }
                }
            }
        } else {
            // $file
            $file = json_decode(json_encode($this->files), true);
            $key = array_keys($file)[$fileIndex - 1];

            $file = Helpers::turnToJSON($file[$key]);
            if ($file) {
                if (!$file->destination) {
                    $response['error'] = true;
                    $response['message'] = "Destination not set";
                } else {
                    if (!is_dir($file->destination)) {
                        mkdir($file->destination, 0777, true);
                    }
                    $fileName = !$file->newName || empty($file->newName) ? $file->name : $file->newName;

                    move_uploaded_file($file->tmp_name, $file->destination . "/" . $fileName);
                    $response['data'] = $file;
                    $response['message'] = "File uploaded successfully";
                }
            }
            unset($file->tmp_name);
        }
        return Helpers::turnToJSON($response);
    }

    public function validate($requestBody, $rules, $messages = null)
    {
        $validator = new Validator;
        return $validator->validate($requestBody, $rules, $messages);
    }
}