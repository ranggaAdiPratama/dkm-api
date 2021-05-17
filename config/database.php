<?php

return [
    
    'default' => 'mysql',

    'connections' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'db_dkm',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ],

    ],

    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
        ],
    ],


];