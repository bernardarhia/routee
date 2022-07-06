<?php

namespace Routee\Http;

class Cors
{
    // take in cors params with null as a default value in the constructor
    public function __construct($allowOrigin = null, $allowMethods = null, $allowHeaders = null, $allowCredentials = null, $maxAge = null)
    {
        $this->allowOrigin = $allowOrigin;
        $this->allowMethods = $allowMethods;
        $this->allowHeaders = $allowHeaders;
        $this->allowCredentials = $allowCredentials;
        $this->maxAge = $maxAge;

        $this->allowOrigin ?? header('Access-Control-Allow-Origin: ' . $this->allowOrigin);
        $this->allowMethods ?? header('Access-Control-Allow-Methods: ' . $this->allowMethods);
        $this->allowHeaders ?? header('Access-Control-Allow-Headers: ' . $this->allowHeaders);
        $this->allowCredentials ?? header('Access-Control-Allow-Credentials: ' . $this->allowCredentials);
        $this->maxAge ?? header('Access-Control-Max-Age: ' . $this->maxAge);
    }
}