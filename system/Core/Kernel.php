<?php
namespace System\Core;

use System\Core\Request;
use System\Core\Response;
use System\Core\Router;
use System\Support\Config;

class Kernel
{
    protected $router;

    public function __construct()
    {
        Config::load(base_path('config'));
        $this->router = new Router();
        $this->loadRoutes();
    }

    protected function loadRoutes()
    {
        $routesFile = base_path('routes/web.php');
        if (file_exists($routesFile)) {
            require $routesFile;
        }
    }

    public function handle(Request $request): Response
{
    $response = $this->router->dispatch($request);

    // If controller returned plain string
    if (is_string($response)) {
        $response = new Response($response);
    }

    // If controller returned nothing
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
