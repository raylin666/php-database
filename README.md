# 数据库 Database Eloquent ORM - 支持 Swoole 协程

[![GitHub release](https://img.shields.io/github/release/raylin666/php-database.svg)](https://github.com/raylin666/php-database/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.3-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >= 7.3

### 安装说明

```
composer require "raylin666/database"
```

### 使用方式

如果你用过 `Laravel`、或者单独用过 `illuminate/database` 包,那么使用起来得心应手,因为写法基本一致。

```php
<?php

require_once 'vendor/autoload.php';

use Raylin666\Database\Pdo\DbConfig;
use Raylin666\Database\Pdo\PDO;
use Raylin666\Database\Pdo\Connection;
use Raylin666\Database\DB;
use Raylin666\Database\Pool\DatabasePool;
use Raylin666\Pool\PoolConfig;
use Raylin666\Pool\PoolOption;
use Raylin666\Database\Model;

// 设置 DB 配置
$config = (new DbConfig())
    ->withName('default')
    ->withHost('127.0.0.1')
    ->withPort(3306)
    ->withDbname('im-server')
    ->withUsername('raylin666')
    ->withPassword('123456')
    ->withTablePrefix('im_')
    ->withOptions([
        // \PDO::ATTR_CASE => \PDO::CASE_UPPER,    // 将字段名称转换为大写
    ]);
// 配置 PDO
$pdo = new PDO($config);
// 创建连接
$connection = (new Connection($pdo))();
// 设置 SQL 日志监听 (如打印SQL日志)
$connection->getConnection()->getEventDispatcher()->listen(
    Illuminate\Database\Events\QueryExecuted::class,
    function ($event) {
        var_dump($event->sql);
    }
);

// 连接池模式
$poolOption = new PoolOption();
$poolOption->withMinConnections(10)
    ->withMaxConnections(100);
$poolConfig = new PoolConfig('local', function () use ($connection) {
    return $connection;
}, $poolOption);
$pool = new DatabasePool($poolConfig);

/***********************************************
 * 非常驻内存环境下使用方式 (非Swoole) 
 ***********************************************/

// 初始化数据库连接配置
DB::newManagerConnection($connection);

// 初始化数据库连接配置
DB::newManagerConnection($pool);

// 查询 SQL
DB::connection('default')->table('account')->first();
DB::connection('local')->table('account')->first();

// Model 模型操作
class Account extends Model
{
    protected $connection = 'default';

    protected $table = 'account';
}

// Model 查询
Account::where(['id' => 6])->first()->toArray();

/***********************************************
 * 常驻内存环境下使用方式 (Swoole, 协程) 
 ***********************************************/
 
$server = new swoole_http_server('127.0.0.1', 9999);

$server->set([
    'worker_num' => swoole_cpu_num(),
]);

// Swoole\Runtime::enableCoroutine(true, SWOOLE_HOOK_ALL);

$server->on('workerStart', function (Swoole\Server $server, int $workerId) use ($pool) {
    var_dump("进程 $workerId 已启动.");

    // 初始化数据库连接配置
    DB::newManagerConnection($pool);
});

$server->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    $count = 0;
    // 模拟并发请求
    $waitGroup = new Swoole\Coroutine\WaitGroup();
    for ($i = 0; $i < 1000; $i++) {
        $waitGroup->add();
        go(function () use ($waitGroup, &$count) {
            Swoole\Coroutine::defer(function () use ($waitGroup, &$count) {
                ++$count;
                $waitGroup->done();
            });

            // $result = DB::table('account')->inRandomOrder()->first();
            $result = Account::inRandomOrder()->first();
            var_dump($result);
        });

        $waitGroup->wait();
        var_dump('EXEC OK.' . $count);
    }
});

$server->start();

```

### 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
