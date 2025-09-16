<?php
namespace System\Core;

class View
{
    protected static $viewsPath = '';

    /**
     * Set views base directory
     */
    public static function setBasePath(string $path)
    {
        self::$viewsPath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Render a view file with given data
     */
    public static function make(string $view, array $data = []): string
    {
        $file = self::$viewsPath . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View file not found: {$file}");
        }

        // Extract variables to local scope
        extract($data, EXTR_SKIP);

        // Capture output buffer
        ob_start();
        include $file;
        return ob_get_clean();
    }
}
