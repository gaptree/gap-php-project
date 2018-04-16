# Gap Project Quickstart

- [Installation](#installation)
- [Nginx](#nginx)
- [Init Project Setting](#init-project-setting)
- [Manage App](#manage-app)
- [Manage Module](#manage-module)
- [Manage Entity](#manage-entity)
    - [Create Html Page](#create-html-page)
    - [Create Service](#create-service)
    - [Create Repo](#create-repo)
- [Testing](#testing)

## Installation

```shell
$ composer create-project gap/project your-project-name 1.0.*
```

## Nginx

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

## Init Project Setting

```shell
$ cp setting/setting.local-default.php setting/setting.local.php
```

```php
<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('debug', true)
    ->set('baseHost', 'tecposter.cn')
    ->set('front', [
        'port' => 8787
    ])
    ->set('local', [
        'db' => [
            'host' => 'db',
            'database' => 'tecposter',
            'username' => 'tecposter',
            'password' => '123456789'
        ],
        'cache' => [
            'host' => 'redis'
        ],
        'session' => [
            'save_handler' => 'redis',
            'save_path' => 'tcp://redis:6379?database=10',
            'subdomain' => 'www'
        ]
    ]);

return $collection;
```

## Manage App

Create App

```shell
$ composer gap buildApp gap/project
or 
$ composer gap buildApp 'Gap\Project'
```

List App

```shell
$ composer gap listApp
```

Remove App

```shell
$ composer gap removeApp 'Gap\Project'
```

## Manage Module

Create Module

```shell
$ composer gap buildModule gap/project/landing
or
$ composer gap buildModule 'Gap\Project\Landing'
```

List Module

```
$ composer gap listModule
```

Remove Module

```shell
$ composer gap removeModule 'Gap\Project\Landing'
```

## Manage Entity

### Create Html Page

#### 1. Create Route

```php
<?php
/*
 * app/gap/project/setting/router/landing.php
 */
$collection = new \Gap\Routing\RouteCollection();
$collection
    ->site('www')
    ->access('public')

    ->get('/', 'home', 'Gap\Project\Landing\Ui\HomeUi@show')
return $collection;
```

#### 2. Create Ui Entity

```shell
$ composer gap buildEntity tec/portal/landing/ui/homeUi
or
$ composer gap buildEntity 'Tec\Portal\Landing\Ui\HomeUi'
```

```php
<?php
/**
 * app/gap/project/src/Landing/Ui/HomeUi.php
 **/
namespace Gap\Project\Landing\Ui;

use Gap\Http\Response;

class HomeUi extends UiBase
{
    public function show(): Response
    {
        return new Response('Hello World');
        // or
        return $this->view('page/landing/home');
    }
}
```

#### 3. Create php template

Location: `app/gap/project/view/page/landing/home.phtml`

```php
<?php $this->layout('layout/gap', [
    'metaTitleVars' => ['hello', 'gap']
]); ?>

<?php $this->section('main'); ?>
<h1>gap</h1>
<h3><?php echo $this->trans('Home Page'); ?></h3>
<p>
    <?php echo $this->trans('Hello', 'Mike'); ?>
    <?php echo $this->localeTrans('en-us', 'Hello', 'v1', 'v2'); ?>
</p>
<?php $this->replace(); ?>
```

More detials about the template engine, please check <https://foilphp.github.io/Foil/>


### Create Service

```shell
$ composer gap buildEntity 'Gap\Project\Landing\Service\CreateArticleService'
```

### Create Repo

```shell
$ composer gap buildEntity 'Gap\Project\Landing\Repo\CreateArticleRepo'
```

## gap/db

```php
<?php
// Create Database Manager
$dmg = new \Gap\Db\DbManager([
    'default' => [
        'driver' => 'mysql',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'database' => '%local.db.database%',
        'host' => '%local.db.host%',
        'username' => '%local.db.username%',
        'password' => '%local.db.password%'
    ],
    'i18n' => [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'driver' => 'mysql',
        'database' => '%local.db.database%',
        'host' => '%local.db.host%',
        'username' => '%local.db.username%',
        'password' => '%local.db.password%'
    ],
]);

// Connect database
$cnn = $dmg->connect('default');

$ssb = $cnn->ssb(); // Select Sql Builder
$isb = $cnn->isb(); // Insert Sql Builder
$usb = $cnn->usb(); // Update Sql Builder
$dsb = $cnn->dsb(); // Delete Sql Buider
```

### Example

select sql builder

```php
<?php
$ssb->select('a.*', 'b.col1', 'b.col2')
    ->from('tableA a', 'tableB b')->end() // end tablePart
    ->where()
        ->expect('a.col1')->equal()->str('v1')
    ->end(); // end condPart (condition part)

$this->assertEquals(
    'SELECT a.*, b.col1, b.col2'
    . ' FROM tableA a, tableB b'
    . ' WHERE a.col1 = :k1'
    . ' LIMIT 10 OFFSET 0',
    $ssb->sql()
);

 $ssb = $cnn->ssb()
    ->select('a.*', 'b.col1', 'b.col2')
    ->from('tableA a', 'tableB b')->end() // end tablePart
    ->where()
        ->expect('a.col1')->like()->str('%hello%')
    ->end(); // end condPart
$this->assertEquals(
    'SELECT a.*, b.col1, b.col2'
    . ' FROM tableA a, tableB b'
    . ' WHERE a.col1 LIKE :k1'
    . ' LIMIT 10 OFFSET 0',
    $ssb->sql()
);


$ssb = $cnn->ssb()
    ->select('a.*', 'b.col1', 'b.col2')
    ->from('tableA a', 'tableB b')
        ->leftJoin('tableC c', 'tableD d')
        ->onCond()
            ->expect('c.col1')->equal()->expr('a.col1')
            ->andExpect('d.col2')->equal()->expr('b.col2')
        ->endJoin()
    ->end()
    ->where()
        ->expect('a.col1')->greater()->int(9)
        ->andGroup()
            ->expect('a.col2')->equal()->str('v2')
            ->orExpect('a.col3')->equal()->int(3)
        ->endGroup()
        ->andExpect('a.col4')->equal()->dateTime(new \DateTime())
    ->end()
    ->ascGroupBy('a.col1')
    ->descOrderBy('a.col2')
    ->limit(28)->offset(3);
$this->assertEquals(
    'SELECT a.*, b.col1, b.col2'
    . ' FROM tableA a, tableB b'
    . ' LEFT JOIN tableC c, tableD d'
    . ' ON c.col1 = a.col1 AND d.col2 = b.col2'
    . ' WHERE a.col1 > :k1'
    . ' AND (a.col2 = :k2 OR a.col3 = :k3)'
    . ' AND a.col4 = :k4'
    . ' GROUP BY a.col1 ASC'
    . ' ORDER BY a.col2 DESC'
    . ' LIMIT 28 OFFSET 3',
    $ssb->sql()
);
```

delete sql builder
```php
<?php
$dsb = $cnn->dsb()
    ->delete('a', 'b', 'c')
    ->from('tableA a', 'tableB b')
        ->leftJoin('tableC c', 'tableD d')
        ->onCond()
            ->expect('c.col1')->equal()->expr('a.col1')
            ->andExpect('d.col2')->equal()->expr('b.col2')
        ->endJoin()
    ->end()
    ->where()
        ->expect('a.col1')->greater()->int(9)
        ->andGroup()
            ->expect('a.col2')->equal()->str('v2')
            ->orExpect('a.col3')->equal()->int(3)
        ->endGroup()
        ->andExpect('a.col4')->equal()->dateTime(new \DateTime())
    ->end()
    ->limit(28)->offset(3)
    ->ascGroupBy('a.col1')
    ->descOrderBy('a.col2');
$this->assertEquals(
    'DELETE a, b, c'
    . ' FROM tableA a, tableB b'
    . ' LEFT JOIN tableC c, tableD d'
    . ' ON c.col1 = a.col1 AND d.col2 = b.col2'
    . ' WHERE a.col1 > :k1'
    . ' AND (a.col2 = :k2 OR a.col3 = :k3)'
    . ' AND a.col4 = :k4'
    . ' GROUP BY a.col1 ASC'
    . ' ORDER BY a.col2 DESC'
    . ' LIMIT 28 OFFSET 3',
    $dsb->sql()
```

insert sql builder
```php
<?php
 $isb = $cnn->isb()
    ->insert('tableA')
    ->field('col1', 'col2', 'col3')
    ->value()
        ->addInt(2)
        ->addStr('val2')
        ->addDateTime(new \DateTime('2018-3-10'))
    ->end()
    ->value()
        ->addInt(3)
        ->addStr('val22')
        ->addDateTime(new \DateTime('2018-3-10'))
    ->end();
$this->assertEquals(
    'INSERT INTO tableA'
    . ' (col1, col2, col3)'
    . ' VALUES '
    . '(:k1, :k2, :k3)'
    . ', (:k4, :k5, :k6)',
    $isb->sql()
);
```

Update Sql Builder

```php
<?php
$usb = $cnn->usb()
    ->update('tableA a', 'tableB b')->end()
    ->set('a.col1')->expr('b.col1')
    ->set('a.col2')->str('val2')
    ->set('a.col3')->int(3);
$this->assertEquals(
    'UPDATE tableA a, tableB b'
    . ' SET a.col1 = b.col1, a.col2 = :k1, a.col3 = :k2',
    $usb->sql()
);

$usb = $cnn->usb()
    ->update('tableA a', 'tableB b')
        ->leftJoin('tableC c', 'tableD d')
        ->onCond()
            ->expect('c.col1')->equal()->expr('a.col1')
            ->andExpect('d.col2')->equal()->expr('b.col2')
        ->endJoin()
    ->end()
    ->set('a.col1')->expr('b.col1')
    ->set('a.col2')->str('val2')
    ->set('a.col3')->int(3)
    ->where()
        ->expect('a.col1')->equal()->str('v1')
    ->end();
$this->assertEquals(
    'UPDATE tableA a, tableB b'
    . ' LEFT JOIN tableC c, tableD d ON c.col1 = a.col1 AND d.col2 = b.col2'
    . ' SET a.col1 = b.col1, a.col2 = :k1, a.col3 = :k2'
    . ' WHERE a.col1 = :k3',
    $usb->sql()
);
```

Sql Syntax
- select <https://dev.mysql.com/doc/refman/5.7/en/select.html>
- insert <https://dev.mysql.com/doc/refman/5.7/en/insert.html>
- delete <https://dev.mysql.com/doc/refman/5.7/en/delete.html>
- update <https://dev.mysql.com/doc/refman/5.7/en/update.html>

```sql
SELECT $selectArr
    FROM $tablePart
    WHERE $condPart
    GROUP BY $groupByArr
    ORDER BY $orderByArr
    LIMIT $limit
    OFFSET $offset

INSERT $fieldArr
    INTO $into
    VALUES $valuePartArr

DELETE $deleteArr
    FROM $tablePart
    WHERE $condPart
    ORDER BY $orderByArr
    LIMIT $limit

UPDATE $tablePart
    SET $setPartArr
    WHERE $condPart
    ORDER BY $orderByArr
    LIMIT $limit
```

**$condPart**

```
[$expectPart, $expectPart ... $condPart, $condPart ...]
```

**tablePart**
```
$tableArr + $joinPartArr

$tableArr: 'tableA a', 'tableB b' ...
$joinPartArr:
[
    $joinPart1: LEFT JOIN $tableArr ON $condPart
    $joinPart2: INNER JOIN ...
    ...
]
```

## Testing

```
$ composer test
```
