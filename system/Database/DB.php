<?php

namespace System\Database;

use PDO;

class DB
{
    private static $pdo;

    public static function connect($config)
    {
        if (!self::$pdo) {
            $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            self::$pdo = new PDO($dsn, $config['username'], $config['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }

    public static function table($table)
    {
        return new QueryBuilder(self::$pdo, $table);
    }
}
