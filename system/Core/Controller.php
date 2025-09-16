<?php
namespace System\Core;

use System\Http\Response;
use System\Core\View;

class Controller
{
    protected function view(string $template, array $data = []): Response
    {
        $content = View::make($template, $data);
        return new Response($content);
    }

    protected function json(array $data, int $status = 200): Response
    {
        return new Response(json_encode($data), $status, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function redirect(string $url): Response
    {
        return new Response('', 302, ['Location' => $url]);
    }
}
