<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('session', [
        'cookie_path' => '/',
        'cookie_lifetime' => 86400000,
        'gc_maxlifetime' => 86400000,
        'name' => 'GAPSESS',
        'cookie_domain' => '%local.session.subdomain%.%baseHost%',
        'save_handler' => '%local.session.save_handler%',
        'save_path' => '%local.session.save_path%'
    ]);

return $collection;
