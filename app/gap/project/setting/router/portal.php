<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Gap\Project\Portal\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Gap\Project\Portal\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Gap\Project\Portal\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Gap\Project\Portal\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Gap\Project\Portal\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Gap\Project\Portal\Open\Entity@postOpen');
*/

$collection
    ->site('default')
    ->access('public')

    ->get('/', 'home', 'Gap\Project\Portal\Ui\HomeUi@show');

return $collection;
