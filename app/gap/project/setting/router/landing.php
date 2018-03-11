<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Gap\Project\Landing\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Gap\Project\Landing\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Gap\Project\Landing\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Gap\Project\Landing\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Gap\Project\Landing\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Gap\Project\Landing\Open\Entity@postOpen');
*/

$collection
    ->site('default')
    ->access('public')

    ->get('/', 'home', 'Gap\Project\Landing\Ui\HomeUi@show');
return $collection;
