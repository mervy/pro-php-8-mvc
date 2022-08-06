<?php

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = $_SERVER['REQUEST_URI'] ?? '/';

function redirectForeverTo($path)
{
    header("Location: {$path}", $replace = true, $code = 301);
    exit;
}

$routes = [
    'GET' => [
        '/' => fn () => print
            <<<HTML
        <body>Hello World</body>
    HTML,
        '/old-home' => fn () => redirectForeverTo('/'),
        '/has-server-error' => fn () => throw new Exception(),
        '/has-validation-error' => fn () => abort(400),
    ],
    'POST' => [],
    'PATCH' => [],
    'PUT' => [],
    'DELETE' => [],
    'HEAD' => [],
    '404' => fn () => include(__DIR__ . '/includes/404.php'),
    '400' => fn () => include(__DIR__ . '/includes/400.php'),
    '500' => fn()=>include(__DIR__.'/includes/500.php'),
];

//this combines all the paths (for all request methods)
//into a single array, so we can quickly see if a path
//exists in any of them
$paths =  array_merge(
    array_keys($routes['GET']),
    array_keys($routes['POST']),
    array_keys($routes['PATCH']),
    array_keys($routes['PUT']),
    array_keys($routes['DELETE']),
    array_keys($routes['HEAD']),
);

function abort($code){
    global $routes;
    $routes[$code]();
}
set_error_handler(function(){
    abort(500);
});
set_exception_handler(function(){
    abort(500);
});

if (isset(
    $routes[$requestMethod],
    $routes[$requestMethod][$requestPath],
)) {
    $routes[$requestMethod][$requestPath](); //Here have () because arrow functions (fn)
} else if (in_array($requestPath, $paths)) {
    //the path is defined, but not for this request method
    //so we show a 400 error (which means "Bad Request")
    $routes['400']();
} else {
    //the path isn't allowed for any request method
    //which probably means they tried a url that the
    //application doesn't support
    $routes['404']();
}
