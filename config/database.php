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


];