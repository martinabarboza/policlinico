<?php

/** Registra rutas HTTP exactas y despacha cada petición a su controlador. */
class Router{

    private array $routes = [];

    /** Registra una acción para solicitudes GET en la URI indicada. */
    public function get(string $uri, array $action): void{
        $this->routes['GET'][$uri] = $action;
    }

    /** Registra una acción para solicitudes POST en la URI indicada. */
    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Ejecuta la acción registrada para método y URI; muestra 404 si no existe.
     * La acción se representa como [NombreControlador::class, 'metodo'].
     */
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