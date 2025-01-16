<?php

use Dotenv\Dotenv;

// 时区
date_default_timezone_set('Asia/Shanghai');
// 严格开发模式
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

if (class_exists(Dotenv::class) && file_exists(run_path('.env'))) {
    if (method_exists(Dotenv::class, 'createUnsafeImmutable')) {
        Dotenv::createUnsafeImmutable(run_path())->load();
    } else {
        Dotenv::createMutable(run_path())->load();
    }
}

return [
    "paths" => [
        "migrations" => "database/migrations",
        "seeds" => "database/seeds"
    ],
    "environments" => [
        "default_migration_table" => "phinx",
        "default_environment" => "production",
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'charset' => getenv('DB_CHARSET'),
        ],
        "dev" => [
            "adapter" => "mysql",
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'charset' => getenv('DB_CHARSET'),
        ]
    ]
];
