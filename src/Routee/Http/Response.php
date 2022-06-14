<?php

namespace Routee\Http;

class Response
{
    public function statusCode($code)
    {
        http_response_code($code);
        return $this;
    }

    public function send($data = null)
    {
        echo (json_encode($data));
        return $this;
    }
    public function json($data)
    {
        if (!is_array($data) && !is_object($data)) throw new \Exception("This function only accesses arrays or objects, " . gettype($data) . " given", 1);
        echo (json_encode($data));
        return $this;
    }
    public function cookie(
        $name,
        $value = null,
        $expires_at = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httponly = false
    ): void {
        // determine the type of value passed and validate it
        $type = gettype($value);
        if (in_array($type, ["string", "integer", "double", "boolean"])) {
            setcookie($name, $value, $expires_at, $path, $domain, $secure, $httponly);
        }
        if ($type == "array" || $type == "object") {
            $value = json_encode($value);
            setcookie($name, ($value), $expires_at, $path, $domain, $secure, $httponly);
        }
    }

    public function session(...$args)
    {
        // $_SESSION

        if (count($args) === 1) {
            $data = $args[0];
            if (!is_array($args)) return false;
            if (array_values($args[0]) != $args[0]) {
                foreach ($args[0] as $key => $value) {
                    $_SESSION[$key] = $value;
                }
            }
        } else {
            $_SESSION[$args[0]] = $args[1];
        }
        return $this;
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}