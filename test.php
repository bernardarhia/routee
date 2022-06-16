<?php

// Middleware class
class ValueMiddleware
{
    private $value;

    public function __construct(string $value)
    {

        $this->value = $value;
    }

    public function __invoke(string $input, callable $next): string
    {
        // Prepend value to the input.
        $output = $next($this->value . $input);

        // Append value to the output.
        return $output . $this->value;
    }
}

// initial action.
$action = fn (string $input): string => $input;

// Array of middleware instances
$middlewares = [
    new ValueMiddleware('1'),
    new ValueMiddleware('2'),
    new ValueMiddleware('3'),
];

foreach ($middlewares as $middleware) {
    $action = fn (string $input): string => $middleware($input, $action);
}

echo $action('value');