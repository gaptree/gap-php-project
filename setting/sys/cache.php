<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('cache', [
    'default' => [
        'host' => '%local.cache.host%',
        'database' => 1,
    ],
    'i18n' => [
        'host' => '%local.cache.host%',
        'database' => 3,
    ],
    'meta' => [
        'host' => '%local.cache.host%',
        'database' => 4,
    ],
    'oauth2' => [
        'host' => '%local.cache.host%',
        'database' => 5,
    ]
]);

return $collection;
