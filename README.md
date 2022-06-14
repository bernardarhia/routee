
# Routee

A lightweight php routing service that works a litle like express js

# Notice

Make sure you are using php version >= 5.4

# Installation

```composer
composer require bernard-arhia/routee
```

# Example
## A simple route service

index.php
```
use Http\Router;
require_once __DIR__ . "/vendor/autoload.php";

$router = new Router;

$router->get("/", function(){
echo "Hello world";
});

$router->run();
```