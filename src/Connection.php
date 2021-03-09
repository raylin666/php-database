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

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseTransactionsManager;
use Illuminate\Database\Query\Processors\Processor;
use Raylin666\Database\Connections\MysqlConnection;
use Raylin666\Database\Connections\PostgresConnection;
use Raylin666\Database\Connections\SQLiteConnection;
use Raylin666\Database\Connections\SqlServerConnection;
use Raylin666\Database\Exception\DatabaseConnectionException;
use Illuminate\Contracts\Events\Dispatcher as DispatcherInterface;

/**
 * Class Connection
 * @package Raylin666\Database
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

        $this->dispatcher = $this->PDO->getConfig()->getDispatcher();

        $this->transactionsManager = new DatabaseTransactionsManager();

        $this->processor = new Processor();
    }

    /**
     * @return Connection
     */
    public function __invoke(): self
    {
        if (! $this->connection instanceof ConnectionInterface) {
            switch ($this->PDO->getConfig()->getDriver()) {
                case Config::DRIVER_MYSQL:
                    $connector = MysqlConnection::class;
                    break;
                case Config::DRIVER_PGSQL:
                    $connector = PostgresConnection::class;
                    break;
                case Config::DRIVER_SQLSRV:
                    $connector = SqlServerConnection::class;
                    break;
                case Config::DRIVER_SQLITE:
                    $connector = SQLiteConnection::class;
                    break;
                default:
                    throw new DatabaseConnectionException($this->PDO->getConfig()->getDriver() . ' driver is not supported or does not exist.');
            }

            $this->connection = $this
                ->createConnectorGetConnection($connector)
                ->setEventDispatcher($this->dispatcher)
                ->setTransactionManager($this->transactionsManager)
                ->setPostProcessor($this->processor);
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
     * @return ConnectionInterface|MysqlConnection|PostgresConnection|SQLiteConnection|SqlServerConnection
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
     * @return ConnectionInterface|MysqlConnection|PostgresConnection|SQLiteConnection|SqlServerConnection
     */
    protected function createConnectorGetConnection($connector): ConnectionInterface
    {
        $connection = new $connector(
            $this->PDO->getConnection(),
            $this->PDO->getConfig()->getDbname(),
            $this->PDO->getConfig()->getTablePrefix()
        );

        /** @var \Illuminate\Database\Connection $connection */
        $connection->setReconnector(function () use ($connection) {
            return $connection;
        });

        return $connection;
    }
}