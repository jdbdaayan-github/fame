<?php
namespace System\Core;

use System\Core\Request;
use System\Core\Response;

class Router
{
    protected $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $uri, callable|array $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, callable|array $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * General route registration
     */
    protected function addRoute(string $method, string $uri, $action)
    {
        $this->routes[$method][$uri] = $action;
    }

    /**
     * Dispatch request
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path   = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $action = $this->routes[$method][$path];

            // Controller@method style
            if (is_string($action) && str_contains($action, '@')) {
                [$controller, $method] = explode('@', $action);
                $controllerClass = "App\\Controllers\\{$controller}";
                $instance = new $controllerClass();
                return call_user_func([$instance, $method], $request);
            }

            // Closure style
            if (is_callable($action)) {
                return call_user_func($action, $request);
            }
        }

        return new Response('404 Not Found', 404);
    }
}
