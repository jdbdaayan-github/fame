<?php
namespace System\Support;

/**
 * Array helper utilities.
 *
 * Usage:
 *   Arr::get($array, 'key', 'default');
 *   Arr::has($array, 'key');
 *   Arr::set($array, 'key', 'value');
 */
class Arr
{
    /**
     * Get an item from an array using dot notation.
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        if ($key === null) {
            return $array;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Check if an item exists in the array using dot notation.
     */
    public static function has(array $array, string $key): bool
    {
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Set an array item to a value using dot notation.
     */
    public static function set(array &$array, string $key, mixed $value): void
    {
        $segments = explode('.', $key);

        foreach ($segments as $i => $segment) {
            if ($i === count($segments) - 1) {
                $array[$segment] = $value;
            } else {
                if (!isset($array[$segment]) || !is_array($array[$segment])) {
                    $array[$segment] = [];
                }
                $array = &$array[$segment];
            }
        }
    }

    /**
     * Remove an item from an array using dot notation.
     */
    public static function forget(array &$array, string $key): void
    {
        $segments = explode('.', $key);

        foreach ($segments as $i => $segment) {
            if ($i === count($segments) - 1) {
                unset($array[$segment]);
            } elseif (isset($array[$segment]) && is_array($array[$segment])) {
                $array = &$array[$segment];
            } else {
                return;
            }
        }
    }
}
