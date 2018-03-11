<?php
$baseDir = realpath(__DIR__ . '/../../');
require $baseDir . '/vendor/autoload.php';

//
// config
//
$configBuilder = new \Gap\Config\ConfigBuilder(
    $baseDir . '/setting',
    $baseDir . '/cache/setting-http.php'
);
$config = $configBuilder->build();

if ($config->bool('debug') !== false) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

//
// app
//
$dmg = null;
if ($dbArr = $config->arr('db')) {
    $legacy = $dbArr['legacy'] ?? false;
    $serverId = $config->config('server')->str('id');
    $dmg = $legacy ?
        new \Gap\Database\DatabaseManager($dbArr, $serverId)
        :
        new \Gap\Db\DbManager($dbArr, $serverId);
}

$cmg = $config->has('cache') ?
    new \Gap\Cache\CacheManager($config->arr('cache'))
    :
    null;

$app = new \Gap\Base\App($config, $dmg, $cmg);

//
// httpHandler
//
$srcOpts = [];
foreach ($config->arr('app') as $appName => $appOpts) {
    $srcOpts[$appName]['dir'] = $appOpts['dir'] . '/setting/router';
}

$routerBuilder = new \Gap\Routing\RouterBuilder(
    $config->str('baseDir'),
    $srcOpts
);
if (false === $config->bool('debug')) {
    $routerBuilder
        ->setCacheFile('cache/setting-router-http.php');
}
$router = $routerBuilder->build();

$siteManager = new \Gap\Http\SiteManager($config->arr('site'));
$httpHandler = new \Gap\Base\HttpHandler($app, $siteManager, $router);

foreach ($config->arr('requestFilter') as $requestFilterClass) {
    $httpHandler->getRequestFilterManager()->addFilter(new $requestFilterClass());
}

foreach ($config->arr('routeFilter') as $routeFilterClass) {
    $httpHandler->getRouteFilterManager()->addFilter(new $routeFilterClass());
}

//
// response
//
$request = new \Gap\Http\Request(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER
);

$request->setSession(
    (new \Gap\Http\Session\SessionBuilder($config->arr('session')))->build()
);

$response = $httpHandler->handle($request);
$response->send();
