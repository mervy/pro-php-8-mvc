<?php

namespace Framework\Routing;

class Router
{
    public function __construct(
        protected array $routes = [],
        protected array $errorHandler = []
    ) {
    }

    public function add(string $method, string $path, callable $handler): Route
    {
        $route = $this->routes[] = new Route($method, $path, $handler);

        return $route;
    }

    public function dispatch()
    {
        $paths = $this->paths();

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = $_SERVER['REQUEST_URI'] ?? '/';

        // this looks through the defined routes and returns
        // the first that matches the requested method and path
        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {
            try {
                // this action could throw and exception
                // so we catch it and display the global error
                // page that we will define in the routes file
                return $matching->dispatch();
            } catch (\Throwable $e) {
                return $this->dispatchError();
            }
        }

        // if the path is defined for a different method
        // we can show a unique error page for it

        if (in_array($requestPath, $paths)) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    private function paths(): array
    {
        $paths = [];
//var_dump($this->routes);
        foreach ($this->routes as $route) {
           // var_dump($route);
            $paths[] = $route->path();
        }
        return $paths;
    }

    private function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        return null;
    }

    public function errorHandler(int $code, mixed $handler)
    {
        $this->errorHandler[$code] = $handler;
    }

    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn () => "400 not allowed";
        return $this->errorHandler[400]();
    }

    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??= fn () => "404 not found";
        return $this->errorHandlers[404]();
    }

    public function dispatchError()
    {
        $this->errorHandler[500] ??= fn () => "500 server error";
        return $this->errorHandler[500]();
    }

    public function redirect($path)
    {
        header(
            "Location: {$path}",
            $replace = true,
            $code = 301
        );
        exit;
    }
}
