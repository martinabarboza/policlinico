<?php

class Router{

    private array $routes = [];

    public function get(string $uri, array $action): void{
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        [$controller, $action] = $this->routes[$method][$uri];

        $controller = new $controller();

        $controller->$action();
    }
}