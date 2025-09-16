<?php
namespace System\Http;

class Request
{
    protected string $method;
    protected string $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = preg_replace('#/index\.php#', '', $uri);

        $this->path = $uri === '' ? '/' : $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
