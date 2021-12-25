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

use Illuminate\Database\Connection as DatabaseConnection;
use Raylin666\Database\Contract\DatabasePoolInterface;
use Raylin666\Database\Exception\DatabaseConnectionException;
use Raylin666\Database\Pdo\Connection;
use Raylin666\Database\Pdo\DbConfig;
use Raylin666\Utils\Context;
use Raylin666\Utils\Coroutine\Coroutine;

/**
 * Class DbManager
 * @package Raylin666\Database\Database
 */
class DbManager
{
    /**
     * @var Connection[]|DatabasePoolInterface[]|null
     */
    protected $connections;

    /**
     * @param Connection|DatabasePoolInterface $connection
     */
    public function addConnection($connection)
    {
        if ($connection instanceof DatabasePoolInterface) {
            $connection_name = $connection->getName();
        } else if ($connection instanceof Connection) {
            $connection_name = $connection->getPDO()->getConfig()->getName();
        } else {
            throw new DatabaseConnectionException('Invalid connection or connection pool object.');
        }

        $this->connections[$this->getConnectionName($connection_name)] = $connection;
    }

    /**
     * @return Connection[]|DatabasePoolInterface[]|null
     */
    public function getConnections()
    {
        return $this->connections;
    }
    
    /**
     * @param null $name
     * @return Connection|DatabasePoolInterface
     */
    public function getConnection($name = null)
    {
        return $this->getConnections()[$this->getConnectionName($this->transformationBasicConnectionName($name))];
    }

    /**
     * @param null $name
     * @return \Illuminate\Database\ConnectionInterface|\Illuminate\Database\Connection|null
     */
    public function connection($name = null)
    {
        $name = $this->transformationBasicConnectionName($name);
        $contextId = $this->getConnectionName($name);
        if (! isset($this->connections[$contextId])) {
            throw new DatabaseConnectionException(sprintf('No %s database connection exists.', $name));
        }

        $connection = $this->connections[$contextId];
        // 普通连接
        if ($connection instanceof Connection) {
            return $this->getConnection($name)->getConnection();
        }
        
        // 连接池
        if ($connection instanceof DatabasePoolInterface) {
            if (Context::has($contextId)) {
                $connection = Context::get($contextId);
            } else {
                $connectionPool = $connection->get();
                try {
                    if ($connection = $connectionPool->getConnection()) {
                        Context::set($contextId, $connection);
                    } else {
                        throw new DatabaseConnectionException(sprintf('Failed to get ` %s ` connection pool connection.', $name));
                    }
                } catch (DatabaseConnectionException $e) {
                    throw new $e;
                } catch (Throwable $e) {
                    throw new $e;
                } finally {
                    if (Coroutine::inCoroutine()) {
                        Coroutine::defer(function () use ($connectionPool) {
                            $connectionPool->release();
                        });
                    } else {
                        $connectionPool->release();
                    }
                }
            }
        } else {
            throw new DatabaseConnectionException('Invalid connection or connection pool object.');
        }

        return $connection;
    }

    /**
     * @param      $table
     * @param null $as
     * @param null $name
     * @return \Illuminate\Database\Query\Builder
     */
    public function table($table, $as = null, $name = null)
    {
        return $this->connection($name)->table($table, $as);
    }

    /**
     * @param null $name
     * @return \Illuminate\Database\Schema\Builder
     */
    public function schema($name = null)
    {
        return $this->connection($name)->getSchemaBuilder();
    }

    /**
     * @param null $name
     * @return \Illuminate\Database\Schema\Grammars\Grammar
     */
    public function grammar($name = null)
    {
        return $this->connection($name)->getSchemaGrammar();
    }

    /**
     * 获取连接名称
     * @param null $name
     * @return string
     */
    protected function getConnectionName($name = null): string
    {
        return sprintf('database.connection.%s', $this->transformationBasicConnectionName($name));
    }

    /**
     * 转换基础连接名称
     * @param $name
     * @return string
     */
    protected function transformationBasicConnectionName($name): string
    {
        return $name ?: DbConfig::DEFAULT_NAME;
    }
}