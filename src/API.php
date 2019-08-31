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
        'search' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/cloudsearch/pc'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/soso/fcgi-bin/client_search_cp'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.search.searchservice.searchsongs/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'GET',
                'url' => 'http://mobilecdn.kugou.com/api/v3/search/song'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ],
        ],
        'song' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/v3/song/detail/'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.songservice.getsongdetail/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'POST',
                'url' => 'http://m.kugou.com/app/i/getSongInfo.php'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'album' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/v1/album/'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/v8/fcg-bin/fcg_v8_album_detail_cp.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.albumservice.getalbumdetail/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'GET',
                'url' => 'http://mobilecdn.kugou.com/api/v3/album/song'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'artist' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/v1/artist/'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/v8/fcg-bin/fcg_v8_singer_track_cp.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.songservice.getartistsongs/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'GET',
                'url' => 'http://mobilecdn.kugou.com/api/v3/singer/song'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'playlist' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/v3/playlist/detail'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/v8/fcg-bin/fcg_v8_playlist_cp.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.list.collectservice.getcollectdetail/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'GET',
                'url' => 'http://mobilecdn.kugou.com/api/v3/special/song'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'playurl' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/song/enhance/player/url'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.songservice.getsongs/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'POST',
                'url' => 'http://media.store.kugou.com/v1/get_res_privilege'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
            ]
        ],
        'lyric' => [
            self::SITE_NETEASE => [
                'method' => 'POST',
                'url' => 'http://music.163.com/api/song/lyric'
            ],
            self::SITE_TENCENT => [
                'method' => 'GET',
                'url' => 'https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric_new.fcg'
            ],
            self::SITE_XIAMI => [
                'method' => 'GET',
                'url' => 'https://acs.m.xiami.com/h5/mtop.alimusic.music.lyricservice.getsonglyrics/1.0/'
            ],
            self::SITE_KUGOU => [
                'method' => 'GET',
                'url' => 'http://krcs.kugou.com/search'
            ],
            self::SITE_BAIDU => [
                'method' => 'GET',
                'url' => 'http://musicapi.taihe.com/v1/restserver/ting'
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
    public function exec($api)
    {
        if (isset($api['encode'])) {
            $api = call_user_func_array([$this, $api['encode']], [$api]);
            if (isset($api['body']['cookie'])) {
                $this->header['Cookie'] = $api['body']['cookie'];
            }
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

        if (!$this->format) return $this->raw;

        $this->data = $this->raw;

        if (isset($api['decode'])) {
            $this->data = json_decode($this->data, true);
            $this->data['api'] = $api;
            $this->data = call_user_func_array([$this, $api['decode']], [json_encode($this->data)]);
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
                $api = $this->searchNetease(
                    self::SITES_API['search'][self::SITE_NETEASE]['url'],
                    $keyword,
                    $option,
                    self::SITES_API['search'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->searchTencent(
                    self::SITES_API['search'][self::SITE_TENCENT]['url'],
                    $keyword,
                    $option,
                    self::SITES_API['search'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->searchXiami(
                    self::SITES_API['search'][self::SITE_XIAMI]['url'],
                    $keyword,
                    $option,
                    self::SITES_API['search'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->searchKugou(
                    self::SITES_API['search'][self::SITE_KUGOU]['url'],
                    $keyword,
                    $option,
                    self::SITES_API['search'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->searchBaidu(
                    self::SITES_API['search'][self::SITE_BAIDU]['url'],
                    $keyword,
                    $option,
                    self::SITES_API['search'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 歌曲(单曲)
     *
     * @param $id
     * @return false|mixed|string
     */
    public function song($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->songNetease(
                    self::SITES_API['song'][self::SITE_NETEASE]['url'],
                    $id,
                    self::SITES_API['song'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->songTencent(
                    self::SITES_API['song'][self::SITE_TENCENT]['url'],
                    $id,
                    self::SITES_API['song'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->songXiami(
                    self::SITES_API['song'][self::SITE_XIAMI]['url'],
                    $id,
                    self::SITES_API['song'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->songKugou(
                    self::SITES_API['song'][self::SITE_KUGOU]['url'],
                    $id,
                    self::SITES_API['song'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->songBaidu(
                    self::SITES_API['song'][self::SITE_BAIDU]['url'],
                    $id,
                    self::SITES_API['song'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 专辑 (setFormat 建议设置为false)
     *
     * @param $id
     * @return false|mixed|string
     */
    public function album($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->albumNetease(
                    self::SITES_API['album'][self::SITE_NETEASE]['url'],
                    $id,
                    self::SITES_API['album'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->albumTencent(
                    self::SITES_API['album'][self::SITE_TENCENT]['url'],
                    $id,
                    self::SITES_API['album'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->albumXiami(
                    self::SITES_API['album'][self::SITE_XIAMI]['url'],
                    $id,
                    self::SITES_API['album'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->albumKugou(
                    self::SITES_API['album'][self::SITE_KUGOU]['url'],
                    $id,
                    self::SITES_API['album'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->albumBaidu(
                    self::SITES_API['album'][self::SITE_BAIDU]['url'],
                    $id,
                    self::SITES_API['album'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 歌手
     *
     * @param $id
     * @return false|mixed|string
     */
    public function artist($id, $limit = 50)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->artistNetease(
                    self::SITES_API['artist'][self::SITE_NETEASE]['url'],
                    $id,
                    $limit,
                    self::SITES_API['artist'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->artistTencent(
                    self::SITES_API['artist'][self::SITE_TENCENT]['url'],
                    $id,
                    $limit,
                    self::SITES_API['artist'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->artistXiami(
                    self::SITES_API['artist'][self::SITE_XIAMI]['url'],
                    $id,
                    $limit,
                    self::SITES_API['artist'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->artistKugou(
                    self::SITES_API['artist'][self::SITE_KUGOU]['url'],
                    $id,
                    $limit,
                    self::SITES_API['artist'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->artistBaidu(
                    self::SITES_API['artist'][self::SITE_BAIDU]['url'],
                    $id,
                    $limit,
                    self::SITES_API['artist'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 歌曲播放列表
     *
     * @param $id
     * @return false|mixed|string
     */
    public function playlist($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->playlistNetease(
                    self::SITES_API['playlist'][self::SITE_NETEASE]['url'],
                    $id,
                    self::SITES_API['playlist'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->playlistTencent(
                    self::SITES_API['playlist'][self::SITE_TENCENT]['url'],
                    $id,
                    self::SITES_API['playlist'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->playlistXiami(
                    self::SITES_API['playlist'][self::SITE_XIAMI]['url'],
                    $id,
                    self::SITES_API['playlist'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->playlistKugou(
                    self::SITES_API['playlist'][self::SITE_KUGOU]['url'],
                    $id,
                    self::SITES_API['playlist'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->playlistBaidu(
                    self::SITES_API['playlist'][self::SITE_BAIDU]['url'],
                    $id,
                    self::SITES_API['playlist'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 歌曲播放地址
     *
     * @param     $id
     * @param int $br
     * @return false|mixed|string
     */
    public function playurl($id, $br = 320)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->playurlNetease(
                    self::SITES_API['playurl'][self::SITE_NETEASE]['url'],
                    $id,
                    $br,
                    self::SITES_API['playurl'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->playurlTencent(
                    self::SITES_API['playurl'][self::SITE_TENCENT]['url'],
                    $id,
                    self::SITES_API['playurl'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->playurlXiami(
                    self::SITES_API['playurl'][self::SITE_XIAMI]['url'],
                    $id,
                    self::SITES_API['playurl'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->playurlKugou(
                    self::SITES_API['playurl'][self::SITE_KUGOU]['url'],
                    $id,
                    self::SITES_API['playurl'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->playurlBaidu(
                    self::SITES_API['playurl'][self::SITE_BAIDU]['url'],
                    $id,
                    self::SITES_API['playurl'][self::SITE_BAIDU]['method']
                );
                break;
        }

        $api['br'] = $br;

        return $this->exec($api);
    }

    /**
     * 歌词
     *
     * @param $id
     * @return false|mixed|string
     */
    public function lyric($id)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $api = $this->lyricNetease(
                    self::SITES_API['lyric'][self::SITE_NETEASE]['url'],
                    $id,
                    self::SITES_API['lyric'][self::SITE_NETEASE]['method']
                );
                break;
            case self::SITE_TENCENT:
                $api = $this->lyricTencent(
                    self::SITES_API['lyric'][self::SITE_TENCENT]['url'],
                    $id,
                    self::SITES_API['lyric'][self::SITE_TENCENT]['method']
                );
                break;
            case self::SITE_XIAMI:
                $api = $this->lyricXiami(
                    self::SITES_API['lyric'][self::SITE_XIAMI]['url'],
                    $id,
                    self::SITES_API['lyric'][self::SITE_XIAMI]['method']
                );
                break;
            case self::SITE_KUGOU:
                $api = $this->lyricKugou(
                    self::SITES_API['lyric'][self::SITE_KUGOU]['url'],
                    $id,
                    self::SITES_API['lyric'][self::SITE_KUGOU]['method']
                );
                break;
            case self::SITE_BAIDU:
                $api = $this->lyricBaidu(
                    self::SITES_API['lyric'][self::SITE_BAIDU]['url'],
                    $id,
                    self::SITES_API['lyric'][self::SITE_BAIDU]['method']
                );
                break;
        }

        return $this->exec($api);
    }

    /**
     * 图片/封面
     *
     * @param     $id
     * @param int $size
     * @return false|mixed|string
     */
    public function pic($id, $size = 300)
    {
        switch ($this->server) {
            case self::SITE_NETEASE:
                $url = $this->picNetease($id, $size);
                break;
            case self::SITE_TENCENT:
                $url = $this->picTencent($id, $size);
                break;
            case self::SITE_XIAMI:
                $url = $this->picXiami($id, $size);
                break;
            case self::SITE_KUGOU:
                $url = $this->picKugou($id);
                break;
            case self::SITE_BAIDU:
                $url = $this->picBaidu($id);
                break;
        }

        return json_encode([
            'url' => $url
        ]);
    }
}