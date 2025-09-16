<?php
namespace System\Http;

use ReflectionMethod;

class Router
{
    protected array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute(string $method, string $uri, callable|array $action): void
    {
        $uri = $uri === '/' ? '/' : '/' . trim($uri, '/');
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(Request $request): Response|string
    {
        $method = $request->getMethod();
        $path   = $request->getPath();

        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $params = $this->matchRoute($route, $path);
            if ($params !== false) {
                return $this->resolveAction($action, $params, $request);
            }
        }

        return new Response('404 Not Found', 404);
    }

    protected function resolveAction(array|callable $action, array $params, Request $request)
    {
        // Closure
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        // Array-style [Controller::class, 'method']
        if (is_array($action) && count($action) === 2) {
            [$controllerClass, $method] = $action;

            if (!class_exists($controllerClass)) {
                return new Response("Controller {$controllerClass} not found", 500);
            }

            $instance = new $controllerClass();

            if (!method_exists($instance, $method)) {
                return new Response("Method {$method} not found in {$controllerClass}", 500);
            }

            $args = [];
            $reflection = new ReflectionMethod($instance, $method);

            foreach ($reflection->getParameters() as $param) {
                $type = $param->getType()?->getName() ?? null;

                if ($type === Request::class) {
                    $args[] = $request;
                } elseif (isset($params[$param->getName()])) {
                    $args[] = $params[$param->getName()];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    $args[] = null;
                }
            }

            return call_user_func_array([$instance, $method], $args);
        }

        return new Response("Invalid route action", 500);
    }

    protected function matchRoute(string $route, string $path): array|false
    {
        $routeParts = array_filter(explode('/', trim($route, '/')));
        $pathParts  = array_filter(explode('/', trim($path, '/')));

        if (empty($routeParts) && empty($pathParts)) {
            return [];
        }

        if (count($routeParts) !== count($pathParts)) return false;

        $params = [];
        foreach ($routeParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $pathParts[$i];
            } elseif ($part !== $pathParts[$i]) {
                return false;
            }
        }

        return $params;
    }
}
