<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('site', [
        'default' => [
            'host' => 'www.%baseHost%',
        ],
        'static' => [
            'host' => 'static.%baseHost%',
            'dir' => '%baseDir%/site/static',
        ],
    ]);

return $collection;
