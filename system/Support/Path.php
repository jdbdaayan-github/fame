<?php
namespace System\Support;

/**
 * Path helper
 */
class Path
{
    public static function base(): string
    {
        return dirname(__DIR__, 2);
    }

    public static function app(): string
    {
        return self::base() . '/app';
    }

    public static function config(): string
    {
        return self::base() . '/config';
    }

    public static function public(): string
    {
        return self::base() . '/public';
    }

    public static function storage(): string
    {
        return self::base() . '/storage';
    }
}
