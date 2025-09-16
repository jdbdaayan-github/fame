<?php
namespace System\Core;

class Request
{
    protected $method;
    protected $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }

    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    public function getPath(): string
    {
        return '/' . trim($this->uri, '/');
    }

    public function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}
