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

use Raylin666\Database\DB;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Class Model
 * @mixin QueryBuilder
 * @package Raylin666\Database\Database
 */
abstract class Model extends EloquentModel
{
    /**
     * @param null $connection
     * @return \Illuminate\Database\Connection|\Illuminate\Database\ConnectionInterface|null
     */
    public static function resolveConnection($connection = null)
    {
        return DB::connection($connection);
    }

    /**
     * @return \Illuminate\Database\Connection|\Illuminate\Database\ConnectionInterface|null
     */
    public function getConnection()
    {
        return static::resolveConnection($this->getConnectionName());
    }

    /**
     * 替换 QueryBuilder
     * @param \Illuminate\Database\Query\Builder $query
     * @return QueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new QueryBuilder($query);
    }
}