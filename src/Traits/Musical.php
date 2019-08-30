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

namespace ShugaChara\Music\Traits;

use ShugaChara\Music\API;

/**
 * Trait Musical
 *
 * @package ShugaChara\Music\Traits
 */
trait Musical
{
    /**
     * 设置Curl头信息
     *
     * @param $server
     * @return array
     */
    protected function curlset($server)
    {
        switch ($server) {
            case API::SITE_NETEASE:
                return $this->setHeaderNetease();
            case API::SITE_TENCENT:
                return $this->setHeaderTencent();
            case API::SITE_XIAMI:
                return $this->setHeaderXiami();
            case API::SITE_KUGOU:
                return $this->setHeaderKugou();
            case API::SITE_BAIDU:
                return $this->setHeaderBaidu();
        }
    }

    /**
     * 设置网易云Header
     *
     * @return array
     */
    protected function setHeaderNetease()
    {
        return [
            'Referer' => 'https://music.163.com/',
            'Cookie' => 'appver=1.5.9; os=osx; __remember_me=true; osver=%E7%89%88%E6%9C%AC%2010.13.5%EF%BC%88%E7%89%88%E5%8F%B7%2017F77%EF%BC%89;',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/605.1.15 (KHTML, like Gecko)',
            'X-Real-IP' => long2ip(mt_rand(1884815360, 1884890111)),
            'Accept' => '*/*',
            'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
            'Connection' => 'keep-alive',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * 设置腾讯Header
     *
     * @return array
     */
    protected function setHeaderTencent()
    {
        return [
            'Referer' => 'http://y.qq.com',
            'Cookie' => 'pgv_pvi=22038528; pgv_si=s3156287488; pgv_pvid=5535248600; yplayer_open=1; ts_last=y.qq.com/portal/player.html; ts_uid=4847550686; yq_index=0; qqmusic_fromtag=66; player_exist=1',
            'User-Agent' => 'QQ%E9%9F%B3%E4%B9%90/54409 CFNetwork/901.1 Darwin/17.6.0 (x86_64)',
            'Accept' => '*/*',
            'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
            'Connection' => 'keep-alive',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * 设置虾米Header
     *
     * @return array
     */
    protected function setHeaderXiami()
    {
        return [
            'Cookie' => '_m_h5_tk=15d3402511a022796d88b249f83fb968_1511163656929; _m_h5_tk_enc=b6b3e64d81dae577fc314b5c5692df3c',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) XIAMI-MUSIC/3.1.1 Chrome/56.0.2924.87 Electron/1.6.11 Safari/537.36',
            'Accept' => 'application/json',
            'Content-type' => 'application/x-www-form-urlencoded',
            'Accept-Language' => 'zh-CN',
        ];
    }

    /**
     * 设置酷狗Header
     *
     * @return array
     */
    protected function setHeaderKugou()
    {
        return [
            'User-Agent' => 'IPhone-8990-searchSong',
            'UNI-UserAgent' => 'iOS11.4-Phone8990-1009-0-WiFi',
        ];
    }

    /**
     * 设置百度Header
     *
     * @return array
     */
    protected function setHeaderBaidu()
    {
        return [
            'Cookie' => 'BAIDUID=' . $this->getRandomHex(32) . ':FG=1',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) baidu-music/1.2.1 Chrome/66.0.3359.181 Electron/3.0.5 Safari/537.36',
            'Accept' => '*/*',
            'Content-type' => 'application/json;charset=UTF-8',
            'Accept-Language' => 'zh-CN',
        ];
    }

    /**
     * 网易云搜索
     *
     * @param        $apiUrl
     * @param        $keyword
     * @param array  $option
     * @param string $method
     * @return array
     */
    protected function searchNetease($apiUrl, $keyword, array $option = [], $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                's' => $keyword,
                'type' => isset($option['type']) ? $option['type'] : 1,
                'limit' => isset($option['limit']) ? $option['limit'] : 30,
                'total' => 'true',
                'offset' => isset($option['page']) && isset($option['limit']) ? ($option['page'] - 1) * $option['limit'] : 0,
            ],
            'encode' => 'netease_AESCBC',
            'format' => 'result.songs',
        ];
    }

    /**
     * 腾讯QQ搜索
     *
     * @param        $apiUrl
     * @param        $keyword
     * @param array  $option
     * @param string $method
     * @return array
     */
    protected function searchTencent($apiUrl, $keyword, array $option = [], $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'format' => 'json',
                'p' => isset($option['page']) ? $option['page'] : 1,
                'n' => isset($option['limit']) ? $option['limit'] : 30,
                'w' => $keyword,
                'aggr' => 1,
                'lossless' => 1,
                'cr' => 1,
                'new_json' => 1,
            ],
            'format' => 'data.song.list',
        ];
    }

    /**
     * 虾米搜索
     *
     * @param        $apiUrl
     * @param        $keyword
     * @param array  $option
     * @param string $method
     * @return array
     */
    protected function searchXiami($apiUrl, $keyword, array $option = [], $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'key' => $keyword,
                    'pagingVO' => [
                        'page' => isset($option['page']) ? $option['page'] : 1,
                        'pageSize' => isset($option['limit']) ? $option['limit'] : 30,
                    ],
                ],
                'r' => 'mtop.alimusic.search.searchservice.searchsongs',
            ],
            'encode' => 'xiami_sign',
            'format' => 'data.data.songs',
        ];
    }

    /**
     * 酷狗搜索
     *
     * @param        $apiUrl
     * @param        $keyword
     * @param array  $option
     * @param string $method
     * @return array
     */
    protected function searchKugou($apiUrl, $keyword, array $option = [], $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'api_ver' => 1,
                'area_code' => 1,
                'correct' => 1,
                'pagesize' => isset($option['limit']) ? $option['limit'] : 30,
                'plat' => 2,
                'tag' => 1,
                'sver' => 5,
                'showtype' => 10,
                'page' => isset($option['page']) ? $option['page'] : 1,
                'keyword' => $keyword,
                'version' => 8990,
            ],
            'format' => 'data.info',
        ];
    }

    /**
     * 百度搜索
     *
     * @param        $apiUrl
     * @param        $keyword
     * @param array  $option
     * @param string $method
     * @return array
     */
    protected function searchBaidu($apiUrl, $keyword, array $option = [], $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.search.merge',
                'isNew' => 1,
                'platform' => 'darwin',
                'page_no' => isset($option['page']) ? $option['page'] : 1,
                'query' => $keyword,
                'version' => '11.2.1',
                'page_size' => isset($option['limit']) ? $option['limit'] : 30,
            ],
            'format' => 'result.song_info.song_list',
        ];
    }

    /**
     * 网易云歌曲
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function songNetease($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'c' => '[{"id":' . $id . ',"v":0}]',
            ],
            'encode' => 'netease_AESCBC',
            'format' => 'songs',
        ];
    }

    /**
     * 腾讯QQ歌曲
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function songTencent($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'songmid' => $id,
                'platform' => 'yqq',
                'format' => 'json',
            ],
            'format' => 'data',
        ];
    }

    /**
     * 虾米歌曲
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function songXiami($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'songId' => $id,
                ],
                'r' => 'mtop.alimusic.music.songservice.getsongdetail',
            ],
            'encode' => 'xiami_sign',
            'format' => 'data.data.songDetail',
        ];
    }

    /**
     * 酷狗歌曲
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function songKugou($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'cmd' => 'playInfo',
                'hash' => $id,
                'from' => 'mkugou',
            ],
            'format' => '',
        ];
    }

    /**
     * 百度歌曲
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function songBaidu($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.song.getInfos',
                'songid' => $id,
                'res' => 1,
                'platform' => 'darwin',
                'version' => '1.0.0',
            ],
            'encode' => 'baidu_AESCBC',
            'format' => 'songinfo',
        ];
    }

    /**
     * 网易云专辑
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function albumNetease($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl . $id,
            'body' => [
                'total' => 'true',
                'offset' => '0',
                'id' => $id,
                'limit' => '1000',
                'ext' => 'true',
                'private_cloud' => 'true',
            ],
            'encode' => 'netease_AESCBC',
            'format' => 'songs',
        ];
    }

    /**
     * 腾讯QQ专辑
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function albumTencent($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'albummid' => $id,
                'platform' => 'mac',
                'format' => 'json',
                'newsong' => 1,
            ],
            'format' => 'data.getSongInfo',
        ];
    }

    /**
     * 虾米专辑
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function albumXiami($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'albumId' => $id,
                ],
                'r' => 'mtop.alimusic.music.albumservice.getalbumdetail',
            ],
            'encode' => 'xiami_sign',
            'format' => 'data.data.albumDetail.songs',
        ];
    }

    /**
     * 酷狗专辑
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function albumKugou($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'albumid' => $id,
                'area_code' => 1,
                'plat' => 2,
                'page' => 1,
                'pagesize' => -1,
                'version' => 8990,
            ],
            'format' => 'data.info',
        ];
    }

    /**
     * 百度专辑
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function albumBaidu($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.album.getAlbumInfo',
                'album_id' => $id,
                'platform' => 'darwin',
                'version' => '11.2.1',
            ],
            'format' => 'songlist',
        ];
    }

    /**
     * 网易云歌手
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $limit
     * @param string $method
     * @return array
     */
    protected function artistNetease($apiUrl, $id, $limit = 50, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl . $id,
            'body' => [
                'ext' => 'true',
                'private_cloud' => 'true',
                'ext' => 'true',
                'top' => $limit,
                'id' => $id,
            ],
            'encode' => 'netease_AESCBC',
            'format' => 'hotSongs',
        ];
    }

    /**
     * 腾讯QQ歌手
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $limit
     * @param string $method
     * @return array
     */
    protected function artistTencent($apiUrl, $id, $limit = 50, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'singermid' => $id,
                'begin' => 0,
                'num' => $limit,
                'order' => 'listen',
                'platform' => 'mac',
                'newsong' => 1,
            ],
            'format' => 'data.list',
        ];
    }

    /**
     * 虾米歌手
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $limit
     * @param string $method
     * @return array
     */
    protected function artistXiami($apiUrl, $id, $limit = 50, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'artistId' => $id,
                    'pagingVO' => [
                        'page' => 1,
                        'pageSize' => $limit,
                    ],
                ],
                'r' => 'mtop.alimusic.music.songservice.getartistsongs',
            ],
            'encode' => 'xiami_sign',
            'format' => 'data.data.songs',
        ];
    }

    /**
     * 酷狗歌手
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $limit
     * @param string $method
     * @return array
     */
    protected function artistKugou($apiUrl, $id, $limit = 50, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'singerid' => $id,
                'area_code' => 1,
                'page' => 1,
                'plat' => 0,
                'pagesize' => $limit,
                'version' => 8990,
            ],
            'format' => 'data.info',
        ];
    }

    /**
     * 百度歌手
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $limit
     * @param string $method
     * @return array
     */
    protected function artistBaidu($apiUrl, $id, $limit = 50, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.artist.getSongList',
                'artistid' => $id,
                'limits' => $limit,
                'platform' => 'darwin',
                'offset' => 0,
                'tinguid' => 0,
                'version' => '11.2.1',
            ],
            'format' => 'songlist',
        ];
    }

    /**
     * 网易云播放列表
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playlistNetease($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                's' => '0',
                'id' => $id,
                'n' => '1000',
                't' => '0',
            ],
            'encode' => 'netease_AESCBC',
            'format' => 'playlist.tracks',
        ];
    }

    /**
     * 腾讯QQ播放列表
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playlistTencent($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'id' => $id,
                'format' => 'json',
                'newsong' => 1,
                'platform' => 'jqspaframe.json',
            ],
            'format' => 'data.cdlist.0.songlist',
        ];
    }

    /**
     * 虾米播放列表
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playlistXiami($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'listId' => $id,
                    'isFullTags' => false,
                    'pagingVO' => [
                        'page' => 1,
                        'pageSize' => 1000,
                    ],
                ],
                'r' => 'mtop.alimusic.music.list.collectservice.getcollectdetail',
            ],
            'encode' => 'xiami_sign',
            'format' => 'data.data.collectDetail.songs',
        ];
    }

    /**
     * 酷狗播放列表
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playlistKugou($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'specialid' => $id,
                'area_code' => 1,
                'page' => 1,
                'plat' => 2,
                'pagesize' => -1,
                'version' => 8990,
            ],
            'format' => 'data.info',
        ];
    }

    /**
     * 百度播放列表
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playlistBaidu($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.diy.gedanInfo',
                'listid' => $id,
                'platform' => 'darwin',
                'version' => '11.2.1',
            ],
            'format' => 'content',
        ];
    }

    /**
     * 网易云歌曲播放地址
     *
     * @param        $apiUrl
     * @param        $id
     * @param int    $br
     * @param string $method
     * @return array
     */
    protected function playurlNetease($apiUrl, $id, $br = 320, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'ids' => [$id],
                'br' => $br * 1000,
            ],
            'encode' => 'netease_AESCBC',
            'decode' => 'netease_url',
        ];
    }

    /**
     * 腾讯QQ歌曲播放地址
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playurlTencent($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'songmid' => $id,
                'platform' => 'yqq',
                'format' => 'json',
            ],
            'decode' => 'tencent_url',
        ];
    }

    /**
     * 虾米歌曲播放地址
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playurlXiami($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'songIds' => [$id],
                ],
                'r' => 'mtop.alimusic.music.songservice.getsongs',
            ],
            'encode' => 'xiami_sign',
            'decode' => 'xiami_url',
        ];
    }

    /**
     * 酷狗歌曲播放地址
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playurlKugou($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => json_encode(
                [
                    'relate' => 1,
                    'userid' => '0',
                    'vip' => 0,
                    'appid' => 1000,
                    'token' => '',
                    'behavior' => 'download',
                    'area_code' => '1',
                    'clientver' => '8990',
                    'resource' => [
                        [
                            'id' => 0,
                            'type' => 'audio',
                            'hash' => $id,
                        ]
                    ]
                ]
            ),
            'decode' => 'kugou_url',
        ];
    }

    /**
     * 百度歌曲播放地址
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function playurlBaidu($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.song.getInfos',
                'songid' => $id,
                'res' => 1,
                'platform' => 'darwin',
                'version' => '1.0.0',
            ],
            'encode' => 'baidu_AESCBC',
            'decode' => 'baidu_url',
        ];
    }

    /**
     * 网易云歌词
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function lyricNetease($apiUrl, $id, $method = 'POST')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'id' => $id,
                'os' => 'linux',
                'lv' => -1,
                'kv' => -1,
                'tv' => -1,
            ],
            'encode' => 'netease_AESCBC',
            'decode' => 'netease_lyric',
        ];
    }

    /**
     * 腾讯QQ歌词
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function lyricTencent($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'songmid' => $id,
                'g_tk' => '5381',
            ],
            'decode' => 'tencent_lyric',
        ];
    }

    /**
     * 虾米歌词
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function lyricXiami($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'data' => [
                    'songId' => $id,
                ],
                'r' => 'mtop.alimusic.music.lyricservice.getsonglyrics',
            ],
            'encode' => 'xiami_sign',
            'decode' => 'xiami_lyric',
        ];
    }

    /**
     * 酷狗歌词
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function lyricKugou($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'keyword' => '%20-%20',
                'ver' => 1,
                'hash' => $id,
                'client' => 'mobi',
                'man' => 'yes',
            ],
            'decode' => 'kugou_lyric',
        ];
    }

    /**
     * 百度歌词
     *
     * @param        $apiUrl
     * @param        $id
     * @param string $method
     * @return array
     */
    protected function lyricBaidu($apiUrl, $id, $method = 'GET')
    {
        return [
            'method' => $method,
            'url' => $apiUrl,
            'body' => [
                'from' => 'qianqianmini',
                'method' => 'baidu.ting.song.lry',
                'songid' => $id,
                'platform' => 'darwin',
                'version' => '1.0.0',
            ],
            'decode' => 'baidu_lyric',
        ];
    }

    /**
     * 网易云图片
     *
     * @param     $id
     * @param int $size
     * @return string
     */
    protected function picNetease($id, $size = 300)
    {
        return 'https://p3.music.126.net/' . $this->netease_encryptId($id) . '/' . $id . '.jpg?param=' . $size . 'y' . $size;
    }

    /**
     * 腾讯QQ图片
     *
     * @param     $id
     * @param int $size
     * @return string
     */
    protected function picTencent($id, $size = 300)
    {
        return 'https://y.gtimg.cn/music/photo_new/T002R' . $size . 'x' . $size . 'M000' . $id . '.jpg?max_age=2592000';
    }

    /**
     * 虾米图片
     *
     * @param     $id
     * @param int $size
     * @return string
     */
    protected function picXiami($id, $size = 300)
    {
        $data = API::getInstance()->setFormat(false)->song($id);
        $data = json_decode($data, true);
        $url = $data['data']['data']['songDetail']['albumLogo'];
        return str_replace('http:', 'https:', $url) . '@1e_1c_100Q_' . $size . 'h_' . $size . 'w';
    }

    /**
     * 酷狗图片
     *
     * @param     $id
     * @return string
     */
    protected function picKugou($id)
    {
        $data = API::getInstance()->setFormat(false)->song($id);
        $data = json_decode($data, true);
        $url = $data['imgUrl'];
        return str_replace('{size}', '400', $url);
    }

    /**
     * 百度图片
     *
     * @param     $id
     * @return string
     */
    protected function picBaidu($id)
    {
        $data = API::getInstance()->setFormat(false)->song($id);
        $data = json_decode($data, true);
        return isset($data['songinfo']['pic_radio']) ? $data['songinfo']['pic_radio'] : $data['songinfo']['pic_small'];
    }

    /**
     * 获取加密随机字节串
     *
     * @param $length
     * @return string
     * @throws \Exception
     */
    protected function getRandomHex($length)
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length / 2, MCRYPT_DEV_URANDOM));
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        }
    }

    /**
     * 进制转换
     *
     * @param $hex
     * @return int|string
     */
    protected function bchexdec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }

        return $dec;
    }

    /**
     * 进制转换
     *
     * @param $dec
     * @return string
     */
    protected function bcdechex($dec)
    {
        $hex = '';
        do {
            $last = bcmod($dec, 16);
            $hex = dechex($last) . $hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while ($dec > 0);

        return $hex;
    }

    /**
     * 格式化进制
     *
     * @param $string
     * @return string
     */
    protected function str2hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }

        return $hex;
    }

    protected function pickup($array, $rule)
    {
        $t = explode('.', $rule);
        foreach ($t as $vo) {
            if (!isset($array[$vo])) {
                return [];
            }
            $array = $array[$vo];
        }

        return $array;
    }

    protected function clean($server, $raw, $rule)
    {
        $raw = json_decode($raw, true);
        if (!empty($rule)) {
            $raw = $this->pickup($raw, $rule);
        }
        if (!isset($raw[0]) && count($raw)) {
            $raw = [$raw];
        }
        $result = array_map([$this, 'format_' . $server], $raw);

        return json_encode($result);
    }

    /**
     * 接口API Request
     *
     * @param       $url
     * @param array $header
     * @param null  $payload
     * @param int   $headerOnly
     * @param null  $proxy
     * @param int   $timeOut
     * @return array
     */
    private function request($url, $header = [], $payload = null, $headerOnly = 0, $proxy = null, $timeOut = 20)
    {
        $header = array_map(function ($k, $v) {
            return $k . ': ' . $v;
        }, array_keys($header), $header);

        $curl = curl_init();
        if (!is_null($payload)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($payload) ? http_build_query($payload) : $payload);
        }
        curl_setopt($curl, CURLOPT_HEADER, $headerOnly);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_IPRESOLVE, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if ($proxy) {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
        }

        for ($i = 0; $i < 3; $i++) {
            $raw = curl_exec($curl);
            $info = curl_getinfo($curl);
            $error = curl_errno($curl);
            $status = $error ? curl_error($curl) : '';
            if (!$error) {
                break;
            }
        }

        curl_close($curl);

        return [
            'raw' => $raw,
            'info' => $info,
            'error' => $error,
            'status' => $status
        ];
    }

    /**
     * 网易云签名加密串
     *
     * @param $api
     * @return mixed
     * @throws \Exception
     */
    protected function netease_AESCBC($api)
    {
        $modulus = '157794750267131502212476817800345498121872783333389747424011531025366277535262539913701806290766479189477533597854989606803194253978660329941980786072432806427833685472618792592200595694346872951301770580765135349259590167490536138082469680638514416594216629258349130257685001248172188325316586707301643237607';
        $pubkey = '65537';
        $nonce = '0CoJUm6Qyw8W8jud';
        $vi = '0102030405060708';

        if (extension_loaded('bcmath')) {
            $skey = $this->getRandomHex(16);
        } else {
            $skey = 'B3v3kH4vRPWRJFfH';
        }

        $body = json_encode($api['body']);

        if (function_exists('openssl_encrypt')) {
            $body = openssl_encrypt($body, 'aes-128-cbc', $nonce, false, $vi);
            $body = openssl_encrypt($body, 'aes-128-cbc', $skey, false, $vi);
        } else {
            $pad = 16 - (strlen($body) % 16);
            $body = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $nonce, $body . str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $vi));
            $pad = 16 - (strlen($body) % 16);
            $body = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $skey, $body . str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $vi));
        }

        if (extension_loaded('bcmath')) {
            $skey = strrev(utf8_encode($skey));
            $skey = $this->bchexdec($this->str2hex($skey));
            $skey = bcpowmod($skey, $pubkey, $modulus);
            $skey = $this->bcdechex($skey);
            $skey = str_pad($skey, 256, '0', STR_PAD_LEFT);
        } else {
            $skey = '85302b818aea19b68db899c25dac229412d9bba9b3fcfe4f714dc016bc1686fc446a08844b1f8327fd9cb623cc189be00c5a365ac835e93d4858ee66f43fdc59e32aaed3ef24f0675d70172ef688d376a4807228c55583fe5bac647d10ecef15220feef61477c28cae8406f6f9896ed329d6db9f88757e31848a6c2ce2f94308';
        }

        $api['url'] = str_replace('/api/', '/weapi/', $api['url']);
        $api['body'] = array(
            'params' => $body,
            'encSecKey' => $skey,
        );

        return $api;
    }

    /**
     * 网易云加密ID串
     *
     * @param $id
     * @return mixed|string
     */
    protected function netease_encryptId($id)
    {
        $magic = str_split('3go8&$8*3*3h0k(2)2');
        $song_id = str_split($id);
        for ($i = 0; $i < count($song_id); $i++) {
            $song_id[$i] = chr(ord($song_id[$i]) ^ ord($magic[$i % count($magic)]));
        }
        $result = base64_encode(md5(implode('', $song_id), 1));
        $result = str_replace(array('/', '+'), array('_', '-'), $result);

        return $result;
    }

    /**
     * 虾米签名加密串
     *
     * @param $api
     * @return mixed
     */
    protected function xiami_sign($api)
    {
        $data = $this->request('https://acs.m.xiami.com/h5/mtop.alimusic.recommend.songservice.getdailysongs/1.0/?appKey=12574478&t=1560663823000&dataType=json&data=%7B%22requestStr%22%3A%22%7B%5C%22header%5C%22%3A%7B%5C%22platformId%5C%22%3A%5C%22mac%5C%22%7D%2C%5C%22model%5C%22%3A%5B%5D%7D%22%7D&api=mtop.alimusic.recommend.songservice.getdailysongs&v=1.0&type=originaljson&sign=22ad1377ee193f3e2772c17c6192b17c', [], null, 1);
        preg_match_all('/_m_h5[^;]+/', $data['raw'], $match);
        $cookie = $match[0][0] . '; ' . $match[0][1];
        $data = json_encode([
            'requestStr' => json_encode([
                'header' => [
                    'platformId' => 'mac',
                ],
                'model' => $api['body']['data'],
            ]),
        ]);
        $appkey = '12574478';
        preg_match('/_m_h5_tk=([^_]+)/', $cookie, $match);
        $token = $match[1];
        $t = time() * 1000;
        $sign = md5(sprintf('%s&%s&%s&%s', $token, $t, $appkey, $data));
        $api['body'] = [
            'appKey' => $appkey,
            't' => $t,
            'dataType' => 'json',
            'data' => $data,
            'api' => $api['body']['r'],
            'v' => '1.0',
            'type' => 'originaljson',
            'sign' => $sign,
            'cookie' => $cookie
        ];

        return $api;
    }

    /**
     * 百度签名加密串
     *
     * @param $api
     * @return mixed
     */
    protected function baidu_AESCBC($api)
    {
        $key = 'DBEECF8C50FD160E';
        $vi = '1231021386755796';

        $data = 'songid=' . $api['body']['songid'] . '&ts=' . intval(microtime(true) * 1000);

        if (function_exists('openssl_encrypt')) {
            $data = openssl_encrypt($data, 'aes-128-cbc', $key, false, $vi);
        } else {
            $pad = 16 - (strlen($data) % 16);
            $data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data . str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $vi));
        }

        $api['body']['e'] = $data;

        return $api;
    }

    /**
     * 网易云URL解析
     *
     * @param $result
     * @return false|string
     */
    protected function netease_url($result)
    {
        $data = json_decode($result, true);
        if (isset($data['data'][0]['uf']['url'])) {
            $data['data'][0]['url'] = $data['data'][0]['uf']['url'];
        }
        if (isset($data['data'][0]['url'])) {
            $url = [
                'url' => $data['data'][0]['url'],
                'size' => $data['data'][0]['size'],
                'br' => $data['data'][0]['br'] / 1000,
            ];
        } else {
            $url = [
                'url' => '',
                'size' => 0,
                'br' => -1,
            ];
        }

        return json_encode($url);
    }

    /**
     * 腾讯QQ URL解析
     *
     * @param $result
     * @return false|string
     */
    protected function tencent_url($result)
    {
        $data = json_decode($result, true);
        $guid = mt_rand() % 10000000000;

        $type = [
            ['size_320mp3', 320, 'M800', 'mp3'],
            ['size_192aac', 192, 'C600', 'm4a'],
            ['size_128mp3', 128, 'M500', 'mp3'],
            ['size_96aac', 96, 'C400', 'm4a'],
            ['size_48aac', 48, 'C200', 'm4a'],
            ['size_24aac', 24, 'C100', 'm4a'],
        ];

        $payload = [
            'req_0' => [
                'module' => 'vkey.GetVkeyServer',
                'method' => 'CgiGetVkey',
                'param' => [
                    'guid' => (string) $guid,
                    'songmid' => [],
                    'filename' => [],
                    'songtype' => [],
                    'uin' => '0',
                    'loginflag' => 1,
                    'platform' => '20',
                ],
            ],
        ];

        foreach ($type as $vo) {
            $payload['req_0']['param']['songmid'][] = $data['data'][0]['mid'];
            $payload['req_0']['param']['filename'][] = $vo[2] . $data['data'][0]['file']['media_mid'] . '.' . $vo[3];
            $payload['req_0']['param']['songtype'][] = $data['data'][0]['type'];
        }

        $api = [
            'method' => 'GET',
            'url' => 'https://u.y.qq.com/cgi-bin/musicu.fcg',
            'body' => [
                'format' => 'json',
                'platform' => 'yqq.json',
                'needNewCode' => 0,
                'data' => json_encode($payload),
            ],
        ];
        $response = json_decode(API::getInstance()->exec($api), true);
        $vkeys = $response['req_0']['data']['midurlinfo'];

        foreach ($type as $index => $vo) {
            if ($data['data'][0]['file'][$vo[0]] && $vo[1] <= array_get($data, 'api.br')) {
                if (! empty($vkeys[$index]['vkey'])) {
                    $url = [
                        'url' => $response['req_0']['data']['sip'][0] . $vkeys[$index]['purl'],
                        'size' => $data['data'][0]['file'][$vo[0]],
                        'br' => $vo[1],
                    ];
                    break;
                }
            }
        }
        if (! isset($url['url'])) {
            $url = [
                'url' => '',
                'size' => 0,
                'br' => -1,
            ];
        }

        return json_encode($url);
    }

    /**
     * 虾米URL解析
     *
     * @param $result
     * @return false|string
     */
    protected function xiami_url($result)
    {
        $url = [];

        $data = json_decode($result, true);

        $type = [
            's' => 740,
            'h' => 320,
            'l' => 128,
            'f' => 64,
            'e' => 32,
        ];
        $max = 0;
        foreach ($data['data']['data']['songs'][0]['listenFiles'] as $vo) {
            if ($type[$vo['quality']] <= array_get($data, 'api.br') && $type[$vo['quality']] > $max) {
                $max = $type[$vo['quality']];
                $url = [
                    'url' => $vo['listenFile'],
                    'size' => $vo['fileSize'],
                    'br' => $type[$vo['quality']],
                ];
            }
        }
        if (! isset($url['url'])) {
            $url = [
                'url' => '',
                'size' => 0,
                'br' => -1,
            ];
        }

        return json_encode($url);
    }

    /**
     * 酷狗URL解析
     *
     * @param $result
     * @return false|string
     */
    protected function kugou_url($result)
    {
        $url = [];

        $data = json_decode($result, true);

        $max = 0;
        foreach ($data['data'][0]['relate_goods'] as $vo) {
            if ($vo['info']['bitrate'] <= array_get($data, 'api.br') && $vo['info']['bitrate'] > $max) {
                $api = [
                    'method' => 'GET',
                    'url' => 'http://trackercdn.kugou.com/i/v2/',
                    'body' => [
                        'hash' => $vo['hash'],
                        'key' => md5($vo['hash'] . 'kgcloudv2'),
                        'pid' => 3,
                        'behavior' => 'play',
                        'cmd' => '25',
                        'version' => 8990,
                    ],
                ];
                $t = json_decode(API::getInstance()->exec($api), true);
                if (isset($t['url'])) {
                    $max = $t['bitRate'] / 1000;
                    $url = [
                        'url' => reset($t['url']),
                        'size' => $t['fileSize'],
                        'br' => $t['bitRate'] / 1000,
                    ];
                }
            }
        }
        if (!isset($url['url'])) {
            $url = [
                'url' => '',
                'size' => 0,
                'br' => -1,
            ];
        }

        return json_encode($url);
    }

    /**
     * 百度URL解析
     *
     * @param $result
     * @return false|string
     */
    protected function baidu_url($result)
    {
        $url = [];

        $data = json_decode($result, true);

        $max = 0;
        foreach ($data['songurl']['url'] as $vo) {
            if ($vo['file_bitrate'] <= array_get($data, 'api.br') && $vo['file_bitrate'] > $max) {
                $url = [
                    'url' => $vo['file_link'],
                    'br' => $vo['file_bitrate'],
                ];
            }
        }
        if (! isset($url['url'])) {
            $url = [
                'url' => '',
                'br' => -1,
            ];
        }

        return json_encode($url);
    }

    /**
     * 网易云歌词解析
     *
     * @param $result
     * @return false|string
     */
    protected function netease_lyric($result)
    {
        $result = json_decode($result, true);
        $data = [
            'lyric' => isset($result['lrc']['lyric']) ? $result['lrc']['lyric'] : '',
            'tlyric' => isset($result['tlyric']['lyric']) ? $result['tlyric']['lyric'] : '',
        ];

        return json_encode($data);
    }

    /**
     * 腾讯QQ歌词解析
     *
     * @param $result
     * @return false|string
     */
    protected function tencent_lyric($result)
    {
        $result = substr($result, 18, -1);
        $result = json_decode($result, true);
        $data = [
            'lyric' => isset($result['lyric']) ? base64_decode($result['lyric']) : '',
            'tlyric' => isset($result['trans']) ? base64_decode($result['trans']) : '',
        ];

        return json_encode($data);
    }

    /**
     * 虾米歌词解析
     *
     * @param $result
     * @return false|string
     */
    protected function xiami_lyric($result)
    {
        $result = json_decode($result, true);

        if (count($result['data']['data']['lyrics'])) {
            $data = $result['data']['data']['lyrics'][0]['content'];
            $data = preg_replace('/<[^>]+>/', '', $data);
            preg_match_all('/\[([\d:\.]+)\](.*)\s\[x-trans\](.*)/i', $data, $match);
            if (count($match[0])) {
                for ($i = 0; $i < count($match[0]); $i++) {
                    $A[] = '[' . $match[1][$i] . ']' . $match[2][$i];
                    $B[] = '[' . $match[1][$i] . ']' . $match[3][$i];
                }
                $arr = [
                    'lyric' => str_replace($match[0], $A, $data),
                    'tlyric' => str_replace($match[0], $B, $data),
                ];
            } else {
                $arr = [
                    'lyric' => $data,
                    'tlyric' => '',
                ];
            }
        } else {
            $arr = [
                'lyric' => '',
                'tlyric' => '',
            ];
        }

        return json_encode($arr);
    }

    /**
     * 酷狗歌词解析
     *
     * @param $result
     * @return false|string
     */
    protected function kugou_lyric($result)
    {
        $result = json_decode($result, true);
        $api = [
            'method' => 'GET',
            'url' => 'http://lyrics.kugou.com/download',
            'body' => [
                'charset' => 'utf8',
                'accesskey' => $result['candidates'][0]['accesskey'],
                'id' => $result['candidates'][0]['id'],
                'client' => 'mobi',
                'fmt' => 'lrc',
                'ver' => 1,
            ],
        ];
        $data = json_decode($this->exec($api), true);
        $arr = [
            'lyric' => base64_decode($data['content']),
            'tlyric' => '',
        ];

        return json_encode($arr);
    }

    /**
     * 百度歌词解析
     *
     * @param $result
     * @return false|string
     */
    protected function baidu_lyric($result)
    {
        $result = json_decode($result, true);
        $data = [
            'lyric' => isset($result['lrcContent']) ? $result['lrcContent'] : '',
            'tlyric' => '',
        ];

        return json_encode($data);
    }

    /**
     * 网易云数据格式化
     *
     * @param $data
     * @return array
     */
    protected function format_netease($data)
    {
        $result = [
            'id' => $data['id'],
            'name' => $data['name'],
            'artist' => [],
            'album' => $data['al']['name'],
            'pic_id' => isset($data['al']['pic_str']) ? $data['al']['pic_str'] : $data['al']['pic'],
            'playurl_id' => $data['id'],
            'lyric_id' => $data['id'],
            'source' => API::SITE_NETEASE,
        ];
        if (isset($data['al']['picUrl'])) {
            preg_match('/\/(\d+)\./', $data['al']['picUrl'], $match);
            $result['pic_id'] = $match[1];
        }
        foreach ($data['ar'] as $vo) {
            $result['artist'][] = $vo['name'];
        }

        return $result;
    }

    /**
     * 腾讯QQ数据格式化
     *
     * @param $data
     * @return array
     */
    protected function format_tencent($data)
    {
        if (isset($data['musicData'])) {
            $data = $data['musicData'];
        }
        $result = [
            'id' => $data['mid'],
            'name' => $data['name'],
            'artist' => [],
            'album' => trim($data['album']['title']),
            'pic_id' => $data['album']['mid'],
            'playurl_id' => $data['mid'],
            'lyric_id' => $data['mid'],
            'source' => API::SITE_TENCENT,
        ];
        foreach ($data['singer'] as $vo) {
            $result['artist'][] = $vo['name'];
        }

        return $result;
    }

    /**
     * 虾米数据格式化
     *
     * @param $data
     * @return array
     */
    protected function format_xiami($data)
    {
        $result = [
            'id' => $data['songId'],
            'name' => $data['songName'],
            'artist' => [],
            'album' => $data['albumName'],
            'pic_id' => $data['songId'],
            'playurl_id' => $data['songId'],
            'lyric_id' => $data['songId'],
            'source' => API::SITE_XIAMI,
        ];
        foreach ($data['singerVOs'] as $vo) {
            $result['artist'][] = $vo['artistName'];
        }

        return $result;
    }

    /**
     * 酷狗数据格式化
     *
     * @param $data
     * @return array
     */
    protected function format_kugou($data)
    {
        $result = [
            'id' => $data['hash'],
            'name' => isset($data['filename']) ? $data['filename'] : $data['fileName'],
            'artist' => [],
            'album' => isset($data['album_name']) ? $data['album_name'] : '',
            'playurl_id' => $data['hash'],
            'pic_id' => $data['hash'],
            'lyric_id' => $data['hash'],
            'source' => API::SITE_KUGOU,
        ];
        list($result['artist'], $result['name']) = explode(' - ', $result['name'], 2);
        $result['artist'] = explode('、', $result['artist']);

        return $result;
    }

    /**
     * 百度数据格式化
     *
     * @param $data
     * @return array
     */
    protected function format_baidu($data)
    {
        return [
            'id' => $data['song_id'],
            'name' => $data['title'],
            'artist' => explode(',', $data['author']),
            'album' => $data['album_title'],
            'pic_id' => $data['song_id'],
            'playurl_id' => $data['song_id'],
            'lyric_id' => $data['song_id'],
            'source' => self::SITE_BAIDU,
        ];
    }
}