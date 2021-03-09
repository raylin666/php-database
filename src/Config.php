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

use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as DispatcherInterface;

/**
 * Class Config
 * @package Raylin666\Database
 */
class Config
{
    /**
     * 默认连接名称
     */
    const DEFAULT_NAME = 'default';

    /**
     * mysql
     */
    const DRIVER_MYSQL = 'mysql';

    /**
     * pgsql
     */
    const DRIVER_PGSQL = 'pgsql';

    /**
     * sqlite
     */
    const DRIVER_SQLITE = 'sqlite';

    /**
     * sqlsrv
     */
    const DRIVER_SQLSRV = 'sqlsrv';

    /**
     * 配置名称
     * @var string
     */
    protected $name = self::DEFAULT_NAME;

    /**
     * 驱动
     * @var string
     */
    protected $driver = self::DRIVER_MYSQL;

    /**
     * 主机
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * 端口
     * @var int
     */
    protected $port = 3306;

    /**
     * unixSocket
     * @var string
     */
    protected $unixSocket;

    /**
     * 数据库名称
     * @var string
     */
    protected $dbname = '';

    /**
     * 表前缀
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * 编码
     * @var string
     */
    protected $charset = 'utf8mb4';

    /**
     * @var string
     */
    protected $collation = 'utf8mb4_unicode_ci';

    /**
     * 账号
     * @var string
     */
    protected $username = 'root';

    /**
     * 密码
     * @var string
     */
    protected $password = 'root';

    /**
     * Pdo 附带参数
     * @var array
     */
    protected $options = [];

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param string $name
     * @return Config
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置表前缀
     * @param string $tablePrefix
     * @return Config
     */
    public function setTablePrefix($tablePrefix = ''): self
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * 获取表前缀
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * 设置编码
     * @param string $charset
     * @return Config
     */
    public function setCharset(string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * 获取编码
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $collation
     * @return Config
     */
    public function setCollation(string $collation): self
    {
        $this->collation = $collation;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollation(): string
    {
        return $this->collation;
    }

    /**
     * 设置数据库名称
     * @param string $dbname
     * @return Config
     */
    public function setDbname(string $dbname): self
    {
        $this->dbname = $dbname;
        return $this;
    }

    /**
     * 获取数据库名称
     * @return string
     */
    public function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * 设置驱动
     * @param string $driver
     * @return Config
     */
    public function setDriver(string $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * 获取驱动
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * 设置主机
     * @param string $host
     * @return Config
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * 获取主机
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * 设置PDO链接参数
     * @param array $options
     * @return Config
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * 获取PDO参数
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * 设置密码
     * @param string $password
     * @return Config
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * 获取密码
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 设置端口
     * @param int $port
     * @return Config
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * 获取端口
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * 设置unixSocket
     * @param string|null $unixSocket
     * @return Config
     */
    public function setUnixSocket(?string $unixSocket): self
    {
        $this->unixSocket = $unixSocket;
        return $this;
    }

    /**
     * 是否存在UnixSocket
     * @return bool
     */
    public function hasUnixSocket(): bool
    {
        return isset($this->unixSocket);
    }

    /**
     * 获取 UnixSocket
     * @return string
     */
    public function getUnixSocket(): string
    {
        return $this->unixSocket;
    }

    /**
     * 设置用户名
     * @param string $username
     * @return Config
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * 获取用户名
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param DispatcherInterface $dispatcher
     * @return Config
     */
    public function setDispatcher(DispatcherInterface $dispatcher): self
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * @return DispatcherInterface
     */
    public function getDispatcher(): DispatcherInterface
    {
        if (! $this->dispatcher) {
            $this->dispatcher = new Dispatcher();
        }

        return $this->dispatcher;
    }

    /**
     * 获取可用的驱动
     * @return string[]
     */
    public static function getAvailableDrivers()
    {
        return [
            self::DRIVER_MYSQL,
            self::DRIVER_PGSQL,
            self::DRIVER_SQLITE,
            self::DRIVER_SQLSRV
        ];
    }
}