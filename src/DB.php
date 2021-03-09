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

namespace Raylin666\Database;

use Throwable;
use Raylin666\Utils\Context;
use Raylin666\Utils\Coroutine\Coroutine;
use Raylin666\Database\Database\Manager;
use Illuminate\Database\ConnectionInterface;
use Raylin666\Database\Exception\DatabaseConnectionException;

/**
 * Class DB
 * @package Raylin666\Database
 */
class DB extends Manager
{
    /**
     * 连接池连接
     * @param null $name
     * @return mixed|null
     * @throws \Exception
     */
    protected static function poolConnection($name = null)
    {
        $contextId = static::getConnectionNameContext($name);

        $connection = null;
        if (Context::has($contextId)) {
            $connection = Context::get($contextId);
        }

        if (! $connection instanceof ConnectionInterface) {
            $connectionPool = static::getDatabasePool($name)->get();

            try {
                if (! $connection = $connectionPool->getConnection()) {
                    throw new DatabaseConnectionException('Failed to get `' . $name . '` connection pool connection.');
                }

                Context::set($contextId, $connection);
            } catch (DatabaseConnectionException $e) {
                // ... get connection errors  Write logs
                if ($logger = static::getLogger()) {
                    $logger->warning(__CLASS__, [$e->getFile(), $e->getLine(), $e->getMessage()]);
                }
            } catch (Throwable $e) {
                // ... Other Write logs
                if ($logger = static::getLogger()) {
                    $logger->warning(__CLASS__, [$e->getFile(), $e->getLine(), $e->getMessage()]);
                }
            } finally {
                if (Coroutine::inCoroutine()) {
                    Coroutine::defer(function () use ($connectionPool) {
                        $connectionPool->release();
                    });
                }
            }
        }

        return $connection;
    }

    /**
     * 数据库连接
     * @param null $name
     * @return \Illuminate\Database\Connection|mixed|null
     * @throws \Exception
     */
    public static function connection($name = null)
    {
        $name = static::getConnectionName($name);

        if (static::hasDatabasePool($name)) {
            return static::poolConnection($name);
        }

        // 非常驻内存环境下
        if (static::hasDbConnection($name)) {
            return static::getDbConnection($name)->getConnection();
        }

        return parent::connection($name);
    }
}