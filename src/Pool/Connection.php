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

namespace Raylin666\Database\Pool;

use Raylin666\Database\Pdo\Connection as DatabaseConnection;

/**
 * Class Connection
 * @package Raylin666\Database\Pool
 */
class Connection extends \Raylin666\Pool\Connection
{
    /**
     * @return mixed|void
     */
    public function connect()
    {
        $connection = ($this->callback)();
        if ($connection instanceof DatabaseConnection) {
            $this->connection = $connection->getConnection();
        }

        return $this->connection;
    }

    /**
     * @return mixed
     */
    protected function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        return $this->reconnect();
    }
}