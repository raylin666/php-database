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

namespace Raylin666\Database\Pdo;

use PDOException;
use Raylin666\Utils\Coroutine\Coroutine;

/**
 * Class PDO
 * @package Raylin666\Database\Pdo
 */
class PDO
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var DbConfig
     */
    protected $config;

    /**
     * PDO constructor.
     * @param DbConfig $config
     */
    public function __construct(DbConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return DbConfig
     */
    public function getConfig(): DbConfig
    {
        return $this->config;
    }

    /**
     * @param int $retry
     * @return \PDO
     */
    public function getConnection(int $retry = 1): \PDO
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
            // 当连接失败时, 一般指连接无效(比如死连接、Sokcet断开) 才执行重试
            if (($retry >= 1) && in_array($e->getCode(), [2006, 2013])) {
                // 当重试一次之后还失败, 则等待 (重试第N次 * 2秒) 后再次重试连接。
                if ($retry < $this->config->getRetry()) {
                    $tryseconds = $retry * 2;
                    Coroutine::inCoroutine() ? Coroutine::sleep($tryseconds) : sleep($tryseconds);
                    $retry++;
                } else {
                    $retry = 0; // 如果还失败将不再重试
                }

                // 重新连接 PDO
                return $this->getConnection($retry);
            }

            throw $e;
        }

        return $this->pdo;
    }
}
