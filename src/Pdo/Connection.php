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

namespace Raylin666\Database\Pdo;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseTransactionsManager;
use Illuminate\Database\Query\Processors\Processor;
use Raylin666\Database\Connectors\MysqlConnection;
use Raylin666\Database\Connectors\PostgresConnection;
use Raylin666\Database\Connectors\SQLiteConnection;
use Raylin666\Database\Connectors\SqlServerConnection;
use Raylin666\Database\Exception\DatabaseConnectionException;
use Illuminate\Contracts\Events\Dispatcher as DispatcherInterface;

/**
 * Class Connection
 * @package Raylin666\Database\Pdo
 */
class Connection
{
    /**
     * @var PDO
     */
    protected $PDO;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var DatabaseTransactionsManager
     */
    protected $transactionsManager;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * Connection constructor.
     * @param PDO $PDO
     */
    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;

        $this->dispatcher = $PDO->getConfig()->getDispatcher();

        $this->transactionsManager = make(DatabaseTransactionsManager::class);

        $this->processor = make(Processor::class);
    }

    /**
     * @return Connection
     */
    public function __invoke(): self
    {
        if (! $this->connection instanceof ConnectionInterface) {
            switch ($this->PDO->getConfig()->getDriver()) {
                case DbConfig::DRIVER_MYSQL:
                    $connector = MysqlConnection::class;
                    break;
                case DbConfig::DRIVER_PGSQL:
                    $connector = PostgresConnection::class;
                    break;
                case DbConfig::DRIVER_SQLSRV:
                    $connector = SqlServerConnection::class;
                    break;
                case DbConfig::DRIVER_SQLITE:
                    $connector = SQLiteConnection::class;
                    break;
                default:
                    throw new DatabaseConnectionException(sprintf('%s driver is not supported or does not exist.', $this->PDO->getConfig()->getDriver()));
            }
            
            $connection = $this->createConnectorGetConnection($connector);
            
            /** @var \Illuminate\Database\Connection $connection */
            // 设置连接器
            $connection->setReconnector(function () use ($connection) {
                return $connection;
            });
            // 设置事件发布器
            $connection->setEventDispatcher($this->dispatcher);
            // 设置事务管理器
            $connection->setTransactionManager($this->transactionsManager);
            // 设置连接使用的查询后处理器
            $connection->setPostProcessor($this->processor);

            $this->connection = $connection;
        }

        return $this;
    }

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->PDO;
    }

    /**
     * @return DispatcherInterface
     */
    public function getDispatcher(): DispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * @return ConnectionInterface|\Illuminate\Database\Connection|null
     */
    public function getConnection(): ?ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return DatabaseTransactionsManager
     */
    public function getTransactionsManager(): DatabaseTransactionsManager
    {
        return $this->transactionsManager;
    }

    /**
     * @return Processor
     */
    public function getProcessor(): Processor
    {
        return $this->processor;
    }

    /**
     * @param $connector
     * @return ConnectionInterface
     */
    protected function createConnectorGetConnection($connector): ConnectionInterface
    {
        return new $connector(
            $this->PDO->getConnection(),
            $this->PDO->getConfig()->getDbname(),
            $this->PDO->getConfig()->getTablePrefix(),
            $this->PDO->getConfig()->getOptions()
        );
    }
}