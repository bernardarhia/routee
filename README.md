
# Routee

A lightweight php routing service for writing fullstack applications in PHP. 

# Notice

Make sure you are using php version >= 8.0.1

# Installation

```sh
composer require bernard-arhia/routee
```
# Example

## A simple route service

index.php

```php
use Http\Router;
require_once  __DIR__  .  "/vendor/autoload.php";

$router  =  new  Router;
$router->get("/", function(){
echo  "Hello world";
});

$router->run();
```
Now open the terminal and start your php web server
```sh
php -S localhost:9000
```
This will start the php server on port 9000
 In your browser open http://localhost:9000 to preview the example
![A screenshot](https://res.cloudinary.com/everich1/image/upload/v1655166918/routee/Screenshot_97_xhenc1.png)

The Route accepts the following http request methods
* GET ($router->get())
* POST ($router->post())
* PUT ($router->put())
* DELETE ($router->delete())
* PATCH ($router->patch())
<!-- * HEAD
* OPTIONS -->

The router accepts basically the following parameters
* ***(string)*** **$path**: the path of the route
* ***(method)*** **$callback**: the callback function to be executed when the route is matched (You can also pass in a class method)