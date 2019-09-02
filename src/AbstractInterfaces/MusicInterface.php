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

namespace ShugaChara\Music\AbstractInterfaces;

/**
 * Interface MusicInterface
 *
 * @package ShugaChara\Music\AbstractInterfaces
 */
interface MusicInterface
{
    /**
     * 搜索音乐
     *
     * @param $keyword
     * @param $platform
     * @return mixed
     */
    public function search($keyword, $platform);

    /**
     * 下载歌曲
     *
     * @param array $song
     * @return mixed
     */
    public function download(array $song);
}
