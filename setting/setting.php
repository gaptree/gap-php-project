<?php
$collection = new \Gap\Config\ConfigCollection();

if (file_exists(__DIR__ . '/setting.local.php')) {
    $collection->requireFile(__DIR__ . '/setting.local.php');
}

if (file_exists(__DIR__ . '/setting.app.php')) {
    $collection->requireFile(__DIR__ . '/setting.app.php');
}

$collection->requireDir(__DIR__ . '/sys');
$collection->requireDir(__DIR__ . '/enabled');
$collection->requireDir(__DIR__ . '/local');

return $collection;
