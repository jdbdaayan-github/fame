<?php
namespace App\Config;

class Database
{
    /**
     * Default connection name
     */
    public string $default = 'mysql';

    /**
     * All available connections
     */
    public array $connections = [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'mydb',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => __DIR__ . '/../../database/database.sqlite',
        ],
    ];
}
