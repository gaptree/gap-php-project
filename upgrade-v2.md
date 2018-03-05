# Upgrade to V2

## composer.json

```json
    "require": {
-       "gap/base": "^1.0"
+       "gap/base": "^2.0"
    },
    "require-dev": {
-       "gap/util": "^1.0",
+       "gap/util": "^2.0",
        "phpunit/phpunit": "^7.0",
        "phpstan/phpstan": "^0.9.2",
        "squizlabs/php_codesniffer": "^3.2",
        "phpmd/phpmd": "^2.6"
    },
```

## site/public/index.php

<https://github.com/gaptree/gap-php-project/blob/master/site/public/index.php>

## gap-php-base

### Gap\Base\App

<https://github.com/gaptree/gap-php-base/blob/master/src/App.php>

- Gap\Base\App
    - +getConfig(): Config
    - +getDmg(): DatabaseManager
    - +getCmg(): CacheManager
    - +getLocaleManager(): ?LocaleManager
    - +getTranslator(): ?Translator

Remove [service locator](http://designpatternsphp.readthedocs.io/en/latest/More/ServiceLocator/README.html)

### Gap\Base\Controller\ControllerBase

<https://github.com/gaptree/gap-php-base/blob/master/src/Controller/ControllerBase.php>

- Gap\Base\Controller\ControllerBase
    - -getApp(): App
    - -getConfig(): Config
    - -getParam(string $key): string
    - -getSiteManager(): SiteManager
    - -getSiteUrlBuilder(): SiteUrlBuilder
    - -getRouteUrlBuilder(): RouteUrlBuilder
    - -getRouter(): Router
    - -getRequest(): Request
    - -getRoute(): Route

#### Redirect url

```php
<?php
namespace ...

use Gap\Http\RedirectResponse;

class RedirectUi extends UiBase
{
    public function show(): RedirectResponse
    {
        ...
        return new RedirectResponse(
            $this->getRouteUrlBuilder()->routeGet('home')
        );
    }
}
```

## gap-php-config

<https://github.com/gaptree/gap-php-config>

- Gap\Config\Config
    - +load(array $items): void
    - +has(string $key): bool
    - +str(string $key, string $default = ''): string
    - +arr(string $key, array $default = []): array
    - +int(string $key, int $default = 0): int
    - +bool(string $key): bool
    - +config(string $key): Config
    - +all(): array
    - +clear(): void

```php
<?php
$debug = $config->bool('debug'); // false

// $config->get('db.default') will not work any more
$dbDefaultConfg = $config->config('db')->config('default');

$dbDefaultConfig->str('driver');
$dbDefaultConfig->str('database');
$dbDefaultConfig->str('host');
$dbDefaultConfig->str('username');
```
