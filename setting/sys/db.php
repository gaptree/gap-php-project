<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('db', [
        'default' => [
            'driver' => 'mysql',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'database' => '%local.db.database%',
            'host' => '%local.db.host%',
            'username' => '%local.db.username%',
            'password' => '%local.db.password%'
        ],
        'i18n' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'driver' => 'mysql',
            'database' => '%local.db.database%',
            'host' => '%local.db.host%',
            'username' => '%local.db.username%',
            'password' => '%local.db.password%'
        ],
        'meta' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'driver' => 'mysql',
            'database' => '%local.db.database%',
            'host' => '%local.db.host%',
            'username' => '%local.db.username%',
            'password' => '%local.db.password%'
        ],
    ]);

return $collection;
