<?php
// +----------------------------------------------------------------------
// | Created by ShugaChara. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2019 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace ShugaChara\Music;

use ShugaChara\Core\Traits\Singleton;
use ShugaChara\Music\Traits\Musical;

/**
 * Class API
 * @method static $this getInstance(...$args)
 * @package ShugaChara\Music
 */
class API
{
    use Singleton, Musical;

    /**
     * 网易云
     */
    const SITE_NETEASE = 'netease';

    /**
     * 腾讯QQ
     */
    const SITE_TENCENT = 'tencent';

    /**
     * 虾米
     */
    const SITE_XIAMI = 'xiami';

    /**
     * 酷狗
     */
    const SITE_KUGOU = 'kugou';

    /**
     * 百度
     */
    const SITE_BAIDU = 'baidu';

    /**
     * 平台集合
     */
    const SITES = [
        self::SITE_NETEASE, self::SITE_TENCENT, self::SITE_XIAMI, self::SITE_KUGOU, self::SITE_BAIDU
    ];

    /**
     * @var 平台服务名
     */
    protected $server;

    /**
     * @var Header头设置
     */
    protected $header = [];

    /**
     * @var 代理
     */
    protected $proxy = null;

    /**
     * @var bool 格式化
     */
    protected $format = false;

    protected $data;

    protected $raw;
    protected $info;
    protected $error;
    protected $status;

    public function __construct()
    {
        $this->setSite(self::SITE_NETEASE);
    }

    /**
     * 设置使用的平台服务
     *
     * @param $platform     平台名称
     * @return $this
     */
    public function setSite($platform)
    {
        $this->server = in_array($platform, self::SITES) ? $platform : self::SITE_NETEASE;
        $this->header = $this->curlset($this->server);
        return $this;
    }

    /**
     * 设置Cookie
     *
     * @param $value
     * @return $this
     */
    public function setCookie($value)
    {
        $this->header['Cookie'] = $value;
        return $this;
    }

    /**
     * 设置格式化
     *
     * @param $value
     * @return $this
     */
    public function setFormat($value)
    {
        $this->format = $value ? true : false;
        return $this;
    }

    /**
     * 设置代理
     *
     * @param $value
     * @return $this
     */
    public function setProxy($value)
    {
        $this->proxy = $value;
        return $this;
    }

    protected function exec($api)
    {
        if (isset($api['encode'])) {
            $api = call_user_func_array([$this, $api['encode']], [$api]);
        }
        if ($api['method'] == 'GET') {
            if (isset($api['body'])) {
                $api['url'] .= '?' . http_build_query($api['body']);
                $api['body'] = null;
            }
        }

        $request = $this->request($api['url'], $this->header, $api['body'], 0, $this->proxy);
        $this->raw = $request['raw'];
        $this->info = $request['info'];
        $this->error = $request['error'];
        $this->status = $request['status'];

        if (! $this->format) return $this->raw;

        $this->data = $this->raw;

        if (isset($api['decode'])) {
            $this->data = call_user_func_array([$this, $api['decode']], [$this->data]);
        }
        if (isset($api['format'])) {
            $this->data = $this->clean($this->server, $this->data, $api['format']);
        }

        return $this->data;
    }
}