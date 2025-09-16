<?php
namespace System\Support;

class Config
{
    protected static array $items = [];

    /**
     * Load all config files from config directory
     */
    public static function load(string $configDir)
    {
        foreach (glob($configDir . '/*.php') as $file) {
            $name = basename($file, '.php');
            self::$items[$name] = require $file;
        }
    }

    /**
     * Get a config value using dot notation
     */
    public static function get(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }

    /**
     * Set a config value dynamically
     */
    public static function set(string $key, $value)
    {
        $segments = explode('.', $key);
        $array =& self::$items;

        foreach ($segments as $segment) {
            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }
            $array =& $array[$segment];
        }

        $array = $value;
    }

    /**
     * Get all configs
     */
    public static function all(): array
    {
        return self::$items;
    }
}
