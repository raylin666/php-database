<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2019 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Database\Database;

use Exception;
use Raylin666\Database\PDO;
use Psr\Log\LoggerInterface;
use Raylin666\Database\Config;
use Raylin666\Database\Connection;
use Raylin666\Database\Contract\DatabasePoolInterface;
use Illuminate\Database\Capsule\Manager as CapsuleManager;

/**
 * Class Manager
 * @package Raylin666\Database\Database
 */
class Manager extends CapsuleManager
{
    /**
     * @var DatabasePoolInterface[]
     */
    protected static $databasePool = [];

    /**
     * @var LoggerInterface
     */
    protected static $logger;

    /**
     * @var Connection[]
     */
    protected static $dbConnection = [];

    /**
     * 新增数据库连接 (非常驻内存环境下使用)
     * @param Config $config
     */
    public static function addDbConnection(Config $config)
    {
        $PDO = new PDO($config);

        $connection = make(
            Connection::class,
            [
                'PDO' => $PDO
            ]
        );

        static::$dbConnection[$config->getName()] = $connection();
    }

    /**
     * 是否有设置数据库连接 (非常驻内存环境下使用)
     * @param $name
     * @return bool
     */
    public static function hasDbConnection($name): bool
    {
        return isset(static::$dbConnection[$name]);
    }

    /**
     * 获取数据库连接 (非常驻内存环境下使用)
     * @param $name
     * @return Connection|null
     */
    public static function getDbConnection($name): ?Connection
    {
        if (static::hasDbConnection($name)) {
            return static::$dbConnection[$name];
        }

        return null;
    }

    /**
     * 设置数据库连接池
     * @param                       $name
     * @param DatabasePoolInterface $databasePool
     */
    public static function setDatabasePool($name, DatabasePoolInterface $databasePool)
    {
        static::$databasePool[$name] = $databasePool;
    }

    /**
     * 是否有设置数据库连接池
     * @param $name
     * @return bool
     */
    public static function hasDatabasePool($name): bool
    {
        return isset(static::$databasePool[$name]);
    }

    /**
     * 获取 Database 连接池
     * @param $name
     * @return DatabasePoolInterface
     * @throws Exception
     */
    protected static function getDatabasePool($name): DatabasePoolInterface
    {
        if (static::hasDatabasePool($name)) {
            return static::$databasePool[$name];
        }

        throw new Exception('Database connection pool named `' . $name . '` is not configured');
    }

    /**
     * 设置日志服务
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        static::$logger = $logger;
    }

    /**
     * 获取日志服务
     * @return LoggerInterface|null
     */
    public static function getLogger(): ?LoggerInterface
    {
        return static::$logger;
    }

    /**
     * @param \Closure|\Illuminate\Database\Query\Builder|string $table
     * @param null                                               $as
     * @param null                                               $connection
     * @return \Illuminate\Database\Query\Builder
     */
    public static function table($table, $as = null, $connection = null)
    {
        return static::connection($connection)->table($table, $as);
    }

    /**
     * @param null $name
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function schema($name = null)
    {
        return static::connection($name)->getSchemaBuilder();
    }

    /**
     * @param null $name
     * @return \Illuminate\Database\Connection|mixed|null
     */
    public function getConnection($name = null)
    {
        // TODO: Implement getConnection() method.

        return static::connection($name);
    }

    /**
     * @param string $method
     * @param array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return static::connection()->$method(...$parameters);
    }

    /**
     * 获取 Database 连接名称
     * @param $name
     * @return string
     */
    protected static function getConnectionName($name): string
    {
        return ! empty($name) ? $name : 'default';
    }

    /**
     * 获取协程切换数据库连接对象名称
     * @param $name
     * @return string
     */
    protected static function getConnectionNameContext($name): string
    {
        return sprintf('database.connection.%s', $name);
    }
}