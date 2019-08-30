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
     * 平台API接口地址
     */
    const SITES_API = [
        'search'        =>      [
            self::SITE_NETEASE      =>      [
                'method'        =>      'POST',
                'url'           =>      'http://music.163.com/api/cloudsearch/pc'
            ],
            self::SITE_TENCENT      =>      [
                'method'        =>      'GET',
                'url'           =>      'https://c.y.qq.com/soso/fcgi-bin/client_search_cp'
            ],
            self::SITE_XIAMI        =>      [
                'method'        =>      'GET',
                'url'           =>      'https://acs.m.xiami.com/h5/mtop.alimusic.search.searchservice.searchsongs/1.0/'
            ],
            self::SITE_KUGOU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://mobilecdn.kugou.com/api/v3/search/song'
            ],
            self::SITE_BAIDU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://musicapi.taihe.com/v1/restserver/ting'
            ],
        ],
        'song'          =>      [
            self::SITE_NETEASE      =>      [
                'method'        =>      'POST',
                'url'           =>      'http://music.163.com/api/v3/song/detail/'
            ],
            self::SITE_TENCENT      =>      [
                'method'        =>      'GET',
                'url'           =>      'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg'
            ],
            self::SITE_XIAMI        =>      [
                'method'        =>      'GET',
                'url'           =>      'https://acs.m.xiami.com/h5/mtop.alimusic.music.songservice.getsongdetail/1.0/'
            ],
            self::SITE_KUGOU        =>      [
                'method'        =>      'POST',
                'url'           =>      'http://m.kugou.com/app/i/getSongInfo.php'
            ],
            self::SITE_BAIDU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'album'          =>      [
            self::SITE_NETEASE      =>      [
                'method'        =>      'POST',
                'url'           =>      'http://music.163.com/api/v1/album/'
            ],
            self::SITE_TENCENT      =>      [
                'method'        =>      'GET',
                'url'           =>      'https://c.y.qq.com/v8/fcg-bin/fcg_v8_album_detail_cp.fcg'
            ],
            self::SITE_XIAMI        =>      [
                'method'        =>      'GET',
                'url'           =>      'https://acs.m.xiami.com/h5/mtop.alimusic.music.albumservice.getalbumdetail/1.0/'
            ],
            self::SITE_KUGOU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://mobilecdn.kugou.com/api/v3/album/song'
            ],
            self::SITE_BAIDU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'artist'          =>      [
            self::SITE_NETEASE      =>      [
                'method'        =>      'POST',
                'url'           =>      'http://music.163.com/api/v1/artist/'
            ],
            self::SITE_TENCENT      =>      [
                'method'        =>      'GET',
                'url'           =>      'https://c.y.qq.com/v8/fcg-bin/fcg_v8_singer_track_cp.fcg'
            ],
            self::SITE_XIAMI        =>      [
                'method'        =>      'GET',
                'url'           =>      'https://acs.m.xiami.com/h5/mtop.alimusic.music.songservice.getartistsongs/1.0/'
            ],
            self::SITE_KUGOU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://mobilecdn.kugou.com/api/v3/singer/song'
            ],
            self::SITE_BAIDU        =>      [
                'method'        =>      'GET',
                'url'           =>      'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ]
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

    /**
     * API执行
     *
     * @param $api
     * @return false|mixed|string
     */
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

    /**
     * 歌曲搜索
     *
     * @param       $keyword
     * @param array $option
     * @return false|mixed|string
     */
    public function search($keyword, array $option = [])
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->searchNetease(self::SITES_API['search'][self::SITE_NETEASE]['url'], $keyword, $option, self::SITES_API['search'][self::SITE_NETEASE]['method']);
                break;
            case self::SITE_TENCENT:
                $api = $this->searchTencent(self::SITES_API['search'][self::SITE_TENCENT]['url'], $keyword, $option, self::SITES_API['search'][self::SITE_TENCENT]['method']);
                break;
            case self::SITE_XIAMI:
                $api = $this->searchXiami(self::SITES_API['search'][self::SITE_XIAMI]['url'], $keyword, $option, self::SITES_API['search'][self::SITE_XIAMI]['method']);
                break;
            case self::SITE_KUGOU:
                $api = $this->searchKugou(self::SITES_API['search'][self::SITE_KUGOU]['url'], $keyword, $option, self::SITES_API['search'][self::SITE_KUGOU]['method']);
                break;
            case self::SITE_BAIDU:
                $api = $this->searchBaidu(self::SITES_API['search'][self::SITE_BAIDU]['url'], $keyword, $option, self::SITES_API['search'][self::SITE_BAIDU]['method']);
                break;
        }

        return $this->exec($api);
    }

    public function song($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->songNetease(self::SITES_API['song'][self::SITE_NETEASE]['url'], $id, self::SITES_API['song'][self::SITE_NETEASE]['method']);
                break;
            case self::SITE_TENCENT:
                $api = $this->songTencent(self::SITES_API['song'][self::SITE_TENCENT]['url'], $id, self::SITES_API['song'][self::SITE_TENCENT]['method']);
                break;
            case self::SITE_XIAMI:
                $api = $this->songXiami(self::SITES_API['song'][self::SITE_XIAMI]['url'], $id, self::SITES_API['song'][self::SITE_XIAMI]['method']);
                break;
            case self::SITE_KUGOU:
                $api = $this->songKugou(self::SITES_API['song'][self::SITE_KUGOU]['url'], $id, self::SITES_API['song'][self::SITE_KUGOU]['method']);
                break;
            case self::SITE_BAIDU:
                $api = $this->songBaidu(self::SITES_API['song'][self::SITE_BAIDU]['url'], $id, self::SITES_API['song'][self::SITE_BAIDU]['method']);
                break;
        }

        return $this->exec($api);
    }

    public function album($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->songNetease(self::SITES_API['song'][self::SITE_NETEASE]['url'], $id, self::SITES_API['song'][self::SITE_NETEASE]['method']);
                break;
            case self::SITE_TENCENT:
                $api = $this->songTencent(self::SITES_API['song'][self::SITE_TENCENT]['url'], $id, self::SITES_API['song'][self::SITE_TENCENT]['method']);
                break;
            case self::SITE_XIAMI:
                $api = $this->songXiami(self::SITES_API['song'][self::SITE_XIAMI]['url'], $id, self::SITES_API['song'][self::SITE_XIAMI]['method']);
                break;
            case self::SITE_KUGOU:
                $api = $this->songKugou(self::SITES_API['song'][self::SITE_KUGOU]['url'], $id, self::SITES_API['song'][self::SITE_KUGOU]['method']);
                break;
            case self::SITE_BAIDU:
                $api = $this->songBaidu(self::SITES_API['song'][self::SITE_BAIDU]['url'], $id, self::SITES_API['song'][self::SITE_BAIDU]['method']);
                break;
        }

        return $this->exec($api);
    }
}