<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('meta', [
    'db' => 'meta',
    'cache' => 'meta',
]);

return $collection;
