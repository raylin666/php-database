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

use Raylin666\Database\Contract\DatabasePoolInterface;
use Raylin666\Database\Database\DbManager;
use Raylin666\Database\Pdo\Connection;

/**
 * Class DB
 * @mixin DbManager
 * @package Raylin666\Database
 */
class DB
{
    /**
     * @var DbManager|null
     */
    protected static $dbManager;

    /**
     * 初始化 DB 连接\连接池管理器
     * @param DatabasePoolInterface|Connection $connection 内部自动判断是普通连接还是连接池
     */
    public static function newManagerConnection($connection)
    {
        (! static::$dbManager instanceof DbManager) && static::$dbManager = new DbManager();
        static::$dbManager->addConnection($connection);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return static::$dbManager->$name(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::$dbManager->$name(...$arguments);
    }
}