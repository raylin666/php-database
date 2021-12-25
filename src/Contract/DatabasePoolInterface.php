<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Database\Contract;

use Raylin666\Contract\PoolInterface;

/**
 * Interface DatabasePoolInterface
 * @package Raylin666\Database\Contract
 */
interface DatabasePoolInterface extends PoolInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}