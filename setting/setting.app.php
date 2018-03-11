<?php
$collection = new \Gap\Config\ConfigCollection();
$collection
    ->set("app", [
        "Gap\Project" => [
            "dir" => "app/gap/project",
        ],
    ]);
return $collection;
