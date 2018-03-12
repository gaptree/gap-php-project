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

## setting

Structure of setting dir

- setting/
    - cmd/ - composer gap command
    - local/ -  local config, can overwrite custom and system config, ignored by git
    - custom/ - custom config, can overwrite system config
    - system/ - system config
    - setting.app.php
    - setting.local.php

Config loading sequence

1. setting.local.php (required)
2. setting.app.php (required)
3. system/
4. custom/
5. local/

## Use meta reference instead of function in *.phtml

```php
<?php
$route = $this->route->getName();

+ $metaTitleVars = is_array($this->metaTitleVars) ? $this->metaTitleVars : [];
+ $metaDescriptionVars = is_array($this->metaDescriptionVars) ? $this->metaDescriptionVars : [];
+ $metaKeywordsVars = is_array($this->metaKeywordsVars) ? $this->metaKeywordsVars : [];

- $metaTitle = $this->meta("title-$route", $this->metaTitleVars);
- $metaDescription = $this->meta("description-$route", $this->metaDescriptionVars);
+ $metaTitle = $this->meta->get("title-$route", ...$metaTitleVars);
+ $metaDescription = $this->meta->get("description-$route", ...$metaDescriptionVars);


- $metaKeywords = null;
- if ($this->isKeywords || $this->metaKeywordsVars) {
-    $metaKeywords = $this->meta("keywords-$route", $this->metaKeywordsVars);
- }
+ if ($metaKeywordsVars) {
+    $metaKeywords = $this->meta->get("keywords-$route", ...$metaKeywordsVars);
+ }
?>
<title><?php echo $metaTitle; ?></title>
<meta name="description" content="<?php echo $metaDescription; ?>">
- <?php if ($metaKeywords) { ?>
+ <?php if ($metaKeywordsVars) { ?>
    <meta name="keywords" content="<?php echo $metaKeywords; ?>">
<?php } ?>

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo $this->meta('siteName'); ?>">
<meta property="og:locale" content="<?php echo $this->getLocaleKey(); ?>">
<meta property="og:titile" content="<?php echo $metaTitle; ?>">
<meta property="og:description" content="<?php echo $metaDescription; ?>">
<meta property="ob:url" content="<?php echo $this->request->getUri(); ?>">

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">

<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
<link rel="icon" type="image/png" href="/img/favicon-16x16.png" size="16x16">
<link rel="icon" type="image/png" href="/img/favicon-32x32.png" size="32x32">
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

## gap-php-db

<https://github.com/gaptree/gap-php-db>

By default,  gap/project will use gap/db. If you want to use gap/database, set db.legacy ture:

```php
<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('db', [
+       'legacy' => true        
    ]);
return $collection;
```

For details check source code in `site/public/index.php`

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
