<?php

use System\Support\Env;
use System\Template\TemplateEngine;


if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return dirname(__DIR__, 2) . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('config_path')) {
    function config_path(string $file = ''): string
    {
        return base_path('config' . ($file ? DIRECTORY_SEPARATOR . $file : ''));
    }
}


if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return Env::get($key, $default);
    }
}

if (! function_exists('view')) {
    /**
     * Render a Fame template view
     */
    function view(string $view, array $data = []): string {
        static $engine = null;

        if ($engine === null) {
            $engine = new TemplateEngine(
                base_path('app/Templates'),
                base_path('storage/cache/views')
            );
        }

        return $engine->render($view, $data);
    }
}

