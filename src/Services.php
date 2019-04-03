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

/*
|--------------------------------------------------------------------------
| shugachara 音乐服务类
|--------------------------------------------------------------------------
 */

namespace ShugaChara\MusicSDK;

use ShugaChara\CoreSDK\Traits\Singleton;
use ShugaChara\MusicSDK\Media\QQ;

class Services
{
    use Singleton;

    // 腾讯QQ平台
    const MUSIC_QQ_PLATFORM = 'QQ';

    // 虾米音乐平台
    const MUSIC_XIAMI_PLATFORM = 'XIAMI';

    // 网易云平台
    const MUSIC_NETEASECLOUD_PLATFORM = 'NETEASECLOUD';

    const MUSIC_PLATFORMS = [
        self::MUSIC_QQ_PLATFORM, self::MUSIC_XIAMI_PLATFORM, self::MUSIC_NETEASECLOUD_PLATFORM
    ];

    const MUSIC_PLATFORMS_RESOURCES = [
        self::MUSIC_QQ_PLATFORM     =>      QQ::class
    ];

    /**
     * @var string 平台类型
     */
    private $platform = self::MUSIC_QQ_PLATFORM;

    /**
     * @var 音乐资源
     */
    private $resources;

    /**
     * 设置平台类型
     *
     * @param $platform
     * @return $this
     */
    public function setPlatform($platform)
    {
        $platform = trim(strtoupper($platform));

        if (in_array($platform, self::MUSIC_PLATFORMS)) {
            $this->platform = $platform;
        }

        return $this;
    }

    /**
     * 获取平台类型
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * 获取平台资源
     *
     * @return 音乐资源|string
     */
    public function getResources()
    {
        try {
            $rs = self::MUSIC_PLATFORMS_RESOURCES[$this->platform];
            $this->resources = new $rs();

        } catch (\Exception $exception) {

            return $exception->getMessage();
        }

        return $this->resources;
    }

}