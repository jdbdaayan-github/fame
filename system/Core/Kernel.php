<?php
namespace System\Core;

use System\Http\Request;
use System\Http\Response;
use System\Http\Router;

class Kernel
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->loadRoutes();
    }

    protected function loadRoutes(): void
    {
        $routesFile = __DIR__ . '/../../routes/web.php';
        if (file_exists($routesFile)) {
            $router = $this->router; // pass instance to routes file
            require $routesFile;
        }
    }

    public function handle(Request $request): Response
    {
        $response = $this->router->dispatch($request);

        if (is_string($response)) {
            $response = new Response($response);
        }

        if (!$response instanceof Response) {
            $response = new Response('', 500, ['Content-Type' => 'text/plain']);
        }

        return $response;
    }

    public function terminate(Response $response)
    {
        $response->send();
    }
}
