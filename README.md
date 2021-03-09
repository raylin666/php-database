# 数据库 Database Eloquent ORM - 支持 Swoole 协程

[![GitHub release](https://img.shields.io/github/release/raylin666/database.svg)](https://github.com/raylin666/database/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.3-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.3

### 安装说明

```
composer require "raylin666/database"
```

### 使用方式

如果你用过Laravel、或者用过illuminate/database包,那么使用起来得心应手,因为写法一致。

```php
<?php

require_once 'vendor/autoload.php';

use Raylin666\Database\DB;
use Raylin666\Database\Config;

/***********************************************
 * 非常驻内存环境下使用方式 (非Swoole) 
 ***********************************************/

// 添加数据库配置
DB::addDbConnection((new Config())
    ->setDriver('mysql')
    ->setName('default')
    ->setTablePrefix('good_')
    ->setHost('127.0.0.1')
    ->setPassword('123456')
    ->setCharset('utf8mb4')
    ->setUsername('root')
    ->setDbname('goods_server')
    ->setPort(3306)
);

DB::addDbConnection((new Config())
    ->setDriver('mysql')
    ->setName('local')
    ->setTablePrefix('order_')
    ->setHost('127.0.0.1')
    ->setPassword('123456')
    ->setCharset('utf8mb4')
    ->setUsername('root')
    ->setDbname('orders_server')
    ->setPort(3306)
);

// SQL 日志监听 (如打印SQL日志)
DB::getDbConnection('default')->getDispatcher()->listen(
    \Illuminate\Database\Events\QueryExecuted::class,
    function ($event) {
        var_dump($event->sql);
    }
);

var_dump(DB::table('user')->first());
var_dump(DB::connection('local')->table('user')->find(1));

class User extends \Raylin666\Database\Model
{
    protected $table = 'user';
}

class LocalUser extends \Raylin666\Database\Model
{
    protected $connection = 'local';
    protected $table = 'user';
}

var_dump(User::select(['id', 'nickname', 'avatar'])->first()->toArray());
var_dump(LocalUser::select(['id', 'username', 'avatar'])->first()->toArray());


/***********************************************
 * 常驻内存环境下使用方式 (Swoole, 协程) 
 ***********************************************/

$dbConfig['default'] = (new Config())
    ->setDriver('mysql')
    ->setName('default')
    ->setTablePrefix('good_')
    ->setHost('127.0.0.1')
    ->setPassword('123456')
    ->setCharset('utf8mb4')
    ->setUsername('root')
    ->setDbname('goods_server')
    ->setPort(3306);
$dbConfig['default']->getDispatcher()->listen(
    \Illuminate\Database\Events\QueryExecuted::class,
    function ($event) {
        var_dump($event->sql);
    }
);

$dbConfig['local'] = (new Config())
    ->setDriver('mysql')
    ->setName('local')
    ->setTablePrefix('order_')
    ->setHost('127.0.0.1')
    ->setPassword('123456')
    ->setCharset('utf8mb4')
    ->setUsername('root')
    ->setDbname('orders_server')
    ->setPort(3306);
$dbConfig['local']->getDispatcher()->listen(
        \Illuminate\Database\Events\QueryExecuted::class,
        function ($event) {
            var_dump($event->sql);
        }
    );

\Swoole\Runtime::enableCoroutine(true, SWOOLE_HOOK_ALL);

$server = new swoole_http_server('127.0.0.1', 10021);

$server->set([
    'worker_num' => 1,
]);

$server->on('workerStart', function () use ($dbConfig) {
    foreach (['default', 'local'] as $connName) {
        $callback = function () use ($connName, $dbConfig) {
            $connection =  new \Raylin666\Database\Connection(
                new \Raylin666\Database\PDO($dbConfig[$connName])
            );
            return $connection();
        };

        $pool = new \Raylin666\Database\Pool\DatabasePool(
            new \Raylin666\Pool\PoolConfig(
                $connName,
                $callback,
                [
                    'min_connections' => 5,
                    'max_connections' => 10,
                    'wait_timeout' => 60,
                ])
        );

        DB::setDatabasePool($connName, $pool);
    }
});

$server->on('request', function () {
    var_dump(DB::table('user')->first());
    var_dump(DB::connection('local')->table('user')->first());

    for ($i = 0; $i < 1000; $i++) {
        // 记得使用go时开启协程 \Swoole\Runtime::enableCoroutine(true, SWOOLE_HOOK_ALL)
        go(function () {
            $user1 = User::select(['id', 'nickname', 'avatar'])->first()->toArray();
            $user2 = LocalUser::select(['id as id1', 'username', 'avatar as avatar1'])->first()->toArray();
            var_dump(array_merge($user1, $user2));
        });
    }
});

$server->start();

```

### 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
