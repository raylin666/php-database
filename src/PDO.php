<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2021 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Database;

use PDOException;

/**
 * Class PDO
 * @package Raylin666\Database
 */
class PDO
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var Config
     */
    protected $config;

    /**
     * PDO constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        try {
            $this->pdo = new \PDO(
                "{$this->config->getDriver()}:" .
                (
                $this->config->hasUnixSocket() ?
                    "unix_socket={$this->config->getUnixSocket()};" :
                    "host={$this->config->getHost()};" . "port={$this->config->getPort()};"
                ) .
                "dbname={$this->config->getDbname()};" .
                "charset={$this->config->getCharset()}",
                $this->config->getUsername(),
                $this->config->getPassword(),
                $this->config->getOptions()
            );
        } catch (PDOException $e) {
            if(strpos($e->getMessage(), 'Connection reset by peer') !== false) {
                // 重新连接 PDO
                return $this->getConnection();
            }

            throw $e;
        }

        return $this->pdo;
    }
}
