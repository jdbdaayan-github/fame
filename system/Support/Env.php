<?php
namespace System\Support;

class Env
{
    protected static $data = [];

    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // skip comments
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$name, $value] = array_map('trim', explode('=', $line, 2));

            // remove quotes if any
            $value = trim($value, "\"'");

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            self::$data[$name] = $value;
        }
    }

    public static function get(string $key, $default = null)
    {
        return self::$data[$key] ?? $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}
