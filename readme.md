# Gap Project Quickstart

- Installation
- Local Development Environment
- Get started

## Installation

```shell
$ composer create-project gap/project your-project-name 1.0.*
```

## Local Development Environment

### Nginx

```nginx
server {
    listen    80;
    server_name    tecposter.cn;
    return 301 $scheme://www.tecposter.cn$request_uri;
}

server {
    listen  80;
    server_name www.tecposter.cn;

    index   index.html index.php;
    root    /var/space/tec-portal-web/site/public;

    access_log  /var/space/tec-portal-web/log/access.log.gz combined gzip;
    error_log /var/space/tec-portal-web/log/error.log;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php(/|$) {
        try_files $uri = 404;

        fastcgi_split_path_info ^(.+\.php)(/.*)$;

        include fastcgi.conf;

        fastcgi_connect_timeout 60;
        fastcgi_send_timeout 180;
        fastcgi_read_timeout 180;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;

        fastcgi_index   index.php;
        fastcgi_pass    php:9000;
    }

    location ~ /\.ht {
        deny all;
    }
}

```

## Get started

Create App

```shell
$ composer gap buildApp tec/portal
```

Create Module
```shell
$ composer gap buildModule tec/portal/landing
```

Create route
```php
<?php
/*
 * app/wec/order/setting/router/landing.php
 */
$collection = new \Gap\Routing\RouteCollection();
$collection
    ->site('www')
    ->access('public')

    ->get('/', 'home', 'Tec\Portal\Landing\Ui\HomeUi@show')
return $collection;
```

Create ui entity

```shell
$ composer gap buildEntity tec/portal/landing/ui/homeUi
```
