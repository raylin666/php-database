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
| shugachara 腾讯QQ音乐接口类
|--------------------------------------------------------------------------
 */

namespace ShugaChara\MusicSDK\Media;

class QQ
{

    const CY_QQ_DOMAIN = 'c.y.qq.com';

    const CY_QQ_HOST = 'https://' . self::CY_QQ_DOMAIN . '/';

    /**
     * 获取歌手
     */
    const URL_GETSINGER = self::CY_QQ_HOST . 'v8/fcg-bin/v8.fcg';

    /**
     * 获取歌手详情
     */
    const URL_GETSINGERINFO = self::CY_QQ_HOST . 'splcloud/fcgi-bin/fcg_get_singer_desc.fcg';

    /**
     * 获取歌手专辑
     */
    const URL_GETSINGERALBUM = self::CY_QQ_HOST . 'v8/fcg-bin/fcg_v8_singer_album.fcg';

    /**
     * 获取歌手头像
     */
    const URL_GETSINGERIMG = 'https://y.gtimg.cn/music/photo_new/';

    /**
     * 获取专辑歌曲
     */
    const URL_GETALBUMMUSIC = self::CY_QQ_HOST . 'v8/fcg-bin/fcg_v8_album_info_cp.fcg';

    /**
     * 获取歌手歌曲
     */
    const URL_GETSINGERMUSIC = self::CY_QQ_HOST . 'v8/fcg-bin/fcg_v8_singer_track_cp.fcg';

    /**
     * 获取专辑
     */
    const URL_GETALBUM = self::CY_QQ_HOST . 'v8/fcg-bin/fcg_v8_album_info_cp.fcg';

    /**
     * 获取歌曲播放地址
     */
    const URL_GETPLAYURL = 'http://dl.stream.qqmusic.qq.com/';

    /**
     * 获取歌曲歌词
     */
    const URL_GETMUSICLYRIC = self::CY_QQ_HOST . 'lyric/fcgi-bin/fcg_query_lyric_new.fcg';

    /**
     * 获取歌曲播放地址中的VKey验证密钥
     */
    const URL_GETMUSICPLAYURLVKEY = self::CY_QQ_HOST . 'base/fcgi-bin/fcg_music_express_mobile3.fcg';

    /**
     * 搜索接口
     */
    const URL_SEARCH = self::CY_QQ_HOST . 'soso/fcgi-bin/client_search_cp';

    /**
     * 初始化参数
     */
    const INIT_ATTRIBUTES = [
        'g_tk'                      =>  1099013371,
        'inCharset'                 =>  'utf-8',
        'outCharset'                =>  'utf-8',
        'notice'                    =>  0,
        'format'                    =>  'json',
        'channel'                   =>  null,
        'page'                      =>  'list',
        'key'                       =>  'all_all_all',
        'pagesize'                  =>  100,
        'hostUin'                   =>  0,
        'loginUin'                  =>  1099013371,
        'needNewCode'               =>  0,
        'platform'                  =>  'yqq',
        'pagenum'                   =>  1,
        'albummid'                  =>  null,
        'order'                     =>  'listen',       // 值: listen | time
        'begin'                     =>  0,              // 获取的数量偏移量
        'num'                       =>  30,             // 获取的数量条数
        'songstatus'                =>  1,
        'singermid'                 =>  null,
        'songmid'                   =>  null,
    ];

    /**
     * @var array 设置请求参数
     */
    protected $attributes = self::INIT_ATTRIBUTES;

    protected $headers = [];

    /**
     * 设置请求参数
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (isset($this->attributes[$key])) {
                $this->attributes[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * 获取歌手数据
     *
     * @param $is_getpage bool 是否获取页数条数码
     * @return mixed
     */
    public function getSinger($is_getpage = false)
    {
        $data = null;

        $this->attributes['channel'] = 'singer';

        $format = [
            'channel'       =>   $this->attributes['channel'],
            'page'          =>   $this->attributes['page'],
            'key'           =>   $this->attributes['key'],
            'pagesize'      =>   $this->attributes['pagesize'],
            'needNewCode'   =>   $this->attributes['needNewCode'],
            'pagenum'       =>   $this->attributes['pagenum'],
        ];

        $list = json_decode($this->getCurl(self::URL_GETSINGER, $format), TRUE);
        if ( ((int) $list['code']) === 0) {
            if ($is_getpage) {
                return [
                    'pagesize'     =>   $list['data']['per_page'],
                    'total'        =>   $list['data']['total'],
                    'total_page'   =>   $list['data']['total_page']
                ];
            }

            $data['list'] = $list['data']['list'];

            if ($data['list']) {
                foreach($data['list'] as $key => $singer) {
                    $data['list'][$key]['coverImg'] = $this->getPicUrl($singer['Fsinger_mid'], 'singer', '500x500');
                    $data['list'][$key]['headImg'] = $this->getPicUrl($singer['Fsinger_mid'], 'singer', '300x300');
                }
            }

            unset($list);
        }

        return $data;
    }

    /**
     * 获取歌手详情内容
     *
     * @param null $singer_mid
     * @return mixed
     */
    public function getSingerInfo($singer_mid = null)
    {
        $data = null;

        $this->attributes['singermid'] = $singer_mid ? : $this->attributes['singermid'];
        $this->attributes['format'] = 'xml';

        $format = [
            'singermid'     =>  $this->attributes['singermid'],
            'format'        =>  $this->attributes['format'],
        ];

        $this->setHeaders();

        libxml_disable_entity_loader(true);

        $info = $this->getCurl(self::URL_GETSINGERINFO, $format);
        $info = json_decode(json_encode(simplexml_load_string($info, 'SimpleXMLElement', LIBXML_NOCDATA)), TRUE);
        if ( ((int) $info['code']) === 0) {
            $data['info'] = $info['data']['info'];
            unset($info);
        }

        return $data;
    }

    /**
     * 获取歌手专辑数据
     *
     * @param string $singer_mid
     * @return bool|mixed
     */
    public function getSingerAlbum($singer_mid = null)
    {
        $data = null;

        $this->attributes['singermid'] = $singer_mid ? : $this->attributes['singermid'];

        $format = [
            'jsonpCallback'     =>  '',
            'loginUin'          =>  $this->attributes['loginUin'],
            'needNewCode'       =>  $this->attributes['needNewCode'],
            'singermid'         =>  $this->attributes['singermid'],
            'order'             =>  $this->attributes['order'],
            'num'               =>  $this->attributes['num'],
            'exstaus'           =>  1,
        ];

        $list = json_decode($this->getCurl(self::URL_GETSINGERALBUM, $format), TRUE);
        if ( ((int) $list['code']) === 0) {
            foreach ($list['data']['list'] as $key => $value) {
                $list['data']['list'][$key]['coverImg'] = $this->getPicUrl($value['albumMID'], 'album', '500x500');
                $list['data']['list'][$key]['minImg'] = $this->getPicUrl($value['albumMID'], 'album', '300x300');
            }
            $data['list'] = $list['data']['list'];

            unset($list);
        }

        return $data;
    }

    /**
     * 获取所属专辑歌曲
     *
     * @param null $album_mid
     * @return mixed
     */
    public function getAlbumMusic($album_mid = null)
    {
        $data = null;

        $this->attributes['albummid'] = $album_mid ? : $this->attributes['albummid'];

        $format = [
            'albummid'      =>  $this->attributes['albummid'],
            'loginUin'      =>  $this->attributes['loginUin'],
        ];

        $list = json_decode($this->getCurl(self::URL_GETALBUMMUSIC, $format), TRUE);
        if ( ((int) $list['code']) === 0) {
            foreach ($list['data']['list'] as $key => $value) {
                $list['data']['list'][$key]['coverImg'] = $this->getPicUrl($value['albumMID'], 'album', '500x500');
                $list['data']['list'][$key]['minImg'] = $this->getPicUrl($value['albumMID'], 'album', '300x300');
            }
            $data['list'] = $list['data']['list'];

            unset($list);
        }

        return $data;
    }

    /**
     * 获取歌手音乐列表
     *
     * @param null $singer_mid
     * @return mixed
     */
    public function getSingerMusic($singer_mid = null)
    {
        $data = null;

        $this->attributes['singermid'] = $singer_mid ? : $this->attributes['singermid'];

        $format = [
            'needNewCode' => $this->attributes['needNewCode'],
            'order'       => $this->attributes['order'],
            'begin'       => $this->attributes['begin'],
            'num'         => $this->attributes['num'],
            'songstatus'  => $this->attributes['songstatus'],
            'singermid'   => $this->attributes['singermid'],
        ];

        $info = json_decode($this->getCurl(self::URL_GETSINGERMUSIC, $format), TRUE);
        if ( ((int) $info['code']) === 0) {
            foreach ($info['data']['list'] as $key => $value) {
                $info['data']['list'][$key]['coverImg'] = $this->getPicUrl($value['musicData']['albummid'], 'music', '500x500');
                $info['data']['list'][$key]['minImg'] = $this->getPicUrl($value['musicData']['albummid'], 'music', '300x300');
            }
            $data['list'] = $info['data']['list'];

            unset($info);
        }

        return $data;
    }

    /**
     * 获取专辑信息
     *
     * @param null $album_mid
     * @return mixed
     */
    public function getAlbum($album_mid = null)
    {
        $data = null;

        $this->attributes['albummid'] = $album_mid ? : $this->attributes['albummid'];

        $format = [
            'needNewCode' => $this->attributes['needNewCode'],
            'loginUin'    => $this->attributes['loginUin'],
            'albummid'    => $this->attributes['albummid'],
        ];

        $info = json_decode($this->getCurl(self::URL_GETALBUM, $format), TRUE);
        if ( ((int) $info['code']) === 0) {
            foreach ($info['data']['list'] as $key => $value) {
                $info['data']['list'][$key]['coverImg'] = $this->getPicUrl($value['albummid'], 'music', '500x500');
                $info['data']['list'][$key]['minImg'] = $this->getPicUrl($value['albummid'], 'music', '300x300');
            }
            $data['info'] = $info['data'];

            unset($info);
        }

        return $data;
    }

    /**
     * 获取歌曲播放地址
     *
     * @param string $song_mid
     * @param string $format        音乐格式
     * @return string
     */
    public function getMusicPlayUrl($song_mid, $format = 'm4a')
    {
        $data = null;

        $ret = $this->getMusicPlayVKey($song_mid, $format);
        if (isset($ret['vkey'])) {
            $data['url'] = self::URL_GETPLAYURL . $ret['filename'] . '?guid=' . $ret['guid'] . '&vkey=' . $ret['vkey'] . '&uin=0&fromtag=53';
        }

        return $data;
    }

    /**
     * 获取歌曲播放地址中的VKey验证密钥
     *
     * @param        $song_mid
     * @param string $format
     * @return array|null
     */
    protected function getMusicPlayVKey($song_mid, $format = 'm4a')
    {
        $ret = null;

        $this->attributes['songmid'] = $song_mid ? : $this->attributes['songmid'];

        switch ($format) {
            case 'mp3' : {              //  MP3 普通高品
                $prefixFormat = 'M500';
                    break;
            }
            case 'mp3-2' : {            //  MP3 高品质
                $prefixFormat = 'M800';
                $format = 'mp3';
                    break;
            }
            case 'ape' : {              //  APE 格式
                $prefixFormat = 'A000';
                    break;
            }
            case 'flac' : {             //  FLAC 格式
                $prefixFormat = 'F000';
                    break;
            }
            default:
                $prefixFormat = 'C400'; //  M4A 格式
                $format = 'm4a';
                    break;
        }

        $filename = $prefixFormat . $this->attributes['songmid'] . '.' . $format;
        $cid = 205361747;

        $format = [
            'songmid'       =>  $this->attributes['songmid'],
            'filename'      =>  $filename,
            'guid'          =>  $this->attributes['loginUin'],
            'loginUin'      =>  $this->attributes['loginUin'],
            'needNewCode'   =>  $this->attributes['needNewCode'],
            'cid'           =>  $cid,
            'uid'           =>  0,
        ];

        $this->setHeaders();

        $data = json_decode($this->getCurl(self::URL_GETMUSICPLAYURLVKEY, $format), TRUE);
        if ( ((int) $data['code']) === 0) {
            $ret = [
                'guid'          =>  $this->attributes['loginUin'],
                'cid'           =>  isset($data['cid']) ? $data['cid'] : $cid,
                'userip'        =>  isset($data['userip']) ? $data['userip'] : '127.0.0.1',
                'expiration'    =>  isset($data['data']['expiration']) ? $data['data']['expiration'] : 80400,
                'songmid'       =>  isset($data['data']['items'][0]['songmid']) ? $data['data']['items'][0]['songmid'] : $this->attributes['songmid'],
                'filename'      =>  isset($data['data']['items'][0]['filename']) ? $data['data']['items'][0]['filename'] : $filename,
                'vkey'          =>  isset($data['data']['items'][0]['vkey']) ? $data['data']['items'][0]['vkey'] : '',
            ];

            unset($data);
        }

        return $ret;
    }

    /**
     *
     *
     * @param null $song_mid
     * @return mixed
     */
    public function getMusicLyric($song_mid = null)
    {
        $data = null;

        $this->attributes['songmid'] = $song_mid ? : $this->attributes['songmid'];

        $format = [
            'needNewCode'   =>  $this->attributes['needNewCode'],
            'pcachetime'    =>  time() . rand(10, 99),
            'songmid'       =>  $this->attributes['songmid'],
        ];

        $this->setHeaders();

        $info = json_decode($this->getCurl(self::URL_GETMUSICLYRIC, $format), TRUE);
        if ( ((int) $info['code']) === 0) {
            $data['lyric'] = base64_decode($info['lyric']);
        }

        return $data;
    }

    /**
     * 搜索接口
     *
     * @param     $keyword  搜索关键词
     * @param int $page     当前页码
     * @param int $num      每页显示数量
     * @return mixed
     */
    public function getSearch($keyword, $page = 1, $num = 20)
    {
        $data = null;

        $format = [
            'w'         =>  $keyword,
            'p'         =>  (int) $page,
            'n'         =>  (int) $num,
        ];

        $list = json_decode($this->getCurl(self::URL_SEARCH, $format), TRUE);
        if ( ((int) $list['code']) === 0 ) {
            $data['list'] = $list['data'];

            unset($list);
        }

        return $data;
    }

    /**
     * 获取图片URL地址
     *
     * @param        $mid   对应类型ID
     * @param string $type  类型: singer 歌手 | album 专辑
     * @param string $size  尺寸大小
     * @return string
     */
    public function getPicUrl($mid, $type = 'singer', $size = '300x300')
    {
        switch ($type) {
            case 'singer' : {
                $url = self::URL_GETSINGERIMG . 'T001R' . $size . 'M000' . $mid . '.jpg?max_age=2592000';
                break;
            }
            case 'music' :
            case 'album' : {
                $url = self::URL_GETSINGERIMG . 'T002R' . $size . 'M000' . $mid . '.jpg?max_age=2592000';
                break;
            }
            default :
        }

        return $url ? : '';
    }

    protected function setHeaders()
    {
        // 强行设置qq域名，绕过qq的安全保护数据
        $this->headers = [
            'Referer: ' . self::CY_QQ_HOST,
            'Host: ' . self::CY_QQ_DOMAIN
        ];

        return $this->headers;
    }

    private function getCurl($url, $format = [])
    {
        $format += [
            'g_tk'        =>   $this->attributes['g_tk'],
            'inCharset'   =>   $this->attributes['inCharset'],
            'outCharset'  =>   $this->attributes['outCharset'],
            'notice'      =>   $this->attributes['notice'],
            'format'      =>   $this->attributes['format'],
            'hostUin'     =>   $this->attributes['hostUin'],
            'platform'    =>   $this->attributes['platform'],
        ];

        $ret = sgc_curl($url . '?' . http_build_query($format), false, 'GET', null, $this->headers);

        // 回归初始化，主要目的:解决Swoole等常驻内存下单例导致的数据残留，影响其他请求返回的结果集。(不同的数据请求，建议用完后销毁重置)
        $this->attributes = self::INIT_ATTRIBUTES;
        $this->headers = [];

        return $ret;
    }

}