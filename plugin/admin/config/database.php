<?php
return  [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'      => 'mysql',
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset'     => getenv('DB_CHARSET'),
            //'collation'   => 'utf8mb4_general_ci',
            'prefix'      => '',
            'strict'      => true,
            'engine'      => null,
        ],
    ],
];