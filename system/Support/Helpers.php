<?php

use System\Support\Env;

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

