<?php

namespace Framework\Routing;

/**
 * Property promotion allows you to combine class fields, 
 * constructor definition and variable assignments all into one syntax, 
 * in the construct parameter list.
 * See https://www.php.net/manual/pt_BR/language.oop5.decon.php
 */
class Route
{
    public function __construct(
        protected string $method,
        protected string $path,
        protected mixed $handler
    ) {
    }

    public function method(string $method): string
    {
        return $this->method;
    }

    //public function path(string $path): string deu erro assim
    public function path(): string
    {
        return $this->path;
    }

    public function matches(string $method, string $path): bool
    {
        return $this->method === $method && $this->path === $path;
    }

    public function dispatch()
    {
        return call_user_func($this->handler);
    }
}
