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
            case API::SITE_NETEASE:     return $this->setHeaderNetease();
            case API::SITE_TENCENT:     return $this->setHeaderTencent();
            case API::SITE_XIAMI:       return $this->setHeaderXiami();
            case API::SITE_KUGOU:       return $this->setHeaderKugou();
            case API::SITE_BAIDU:       return $this->setHeaderBaidu();
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
            'url'    => $apiUrl,
            'body'   => [
                's'      => $keyword,
                'type'   => isset($option['type']) ? $option['type'] : 1,
                'limit'  => isset($option['limit']) ? $option['limit'] : 30,
                'total'  => 'true',
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
            'url'    => $apiUrl,
            'body'   => [
                'format'   => 'json',
                'p'        => isset($option['page']) ? $option['page'] : 1,
                'n'        => isset($option['limit']) ? $option['limit'] : 30,
                'w'        => $keyword,
                'aggr'     => 1,
                'lossless' => 1,
                'cr'       => 1,
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
            'url'    => $apiUrl,
            'body'   => [
                'data' => [
                    'key'      => $keyword,
                    'pagingVO' => [
                        'page'     => isset($option['page']) ? $option['page'] : 1,
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
            'url'    => $apiUrl,
            'body'   => [
                'api_ver'   => 1,
                'area_code' => 1,
                'correct'   => 1,
                'pagesize'  => isset($option['limit']) ? $option['limit'] : 30,
                'plat'      => 2,
                'tag'       => 1,
                'sver'      => 5,
                'showtype'  => 10,
                'page'      => isset($option['page']) ? $option['page'] : 1,
                'keyword'   => $keyword,
                'version'   => 8990,
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
            'url'    => $apiUrl,
            'body'   => [
                'from'      => 'qianqianmini',
                'method'    => 'baidu.ting.search.merge',
                'isNew'     => 1,
                'platform'  => 'darwin',
                'page_no'   => isset($option['page']) ? $option['page'] : 1,
                'query'     => $keyword,
                'version'   => '11.2.1',
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
            'url'    => $apiUrl,
            'body'   => [
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
            'url'    => $apiUrl,
            'body'   => [
                'songmid'  => $id,
                'platform' => 'yqq',
                'format'   => 'json',
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
            'url'    => $apiUrl,
            'body'   => [
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
            'url'    => $apiUrl,
            'body'   => [
                'cmd'  => 'playInfo',
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
            'url'    => $apiUrl,
            'body'   => [
                'from'     => 'qianqianmini',
                'method'   => 'baidu.ting.song.getInfos',
                'songid'   => $id,
                'res'      => 1,
                'platform' => 'darwin',
                'version'  => '1.0.0',
            ],
            'encode' => 'baidu_AESCBC',
            'format' => 'songinfo',
        ];
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

    protected function pickup($array, $rule)
    {
        $t = explode('.', $rule);
        foreach ($t as $vo) {
            if (! isset($array[$vo])) {
                return [];
            }
            $array = $array[$vo];
        }

        return $array;
    }

    protected function clean($server, $raw, $rule)
    {
        $raw = json_decode($raw, true);
        if (! empty($rule)) {
            $raw = $this->pickup($raw, $rule);
        }
        if (! isset($raw[0]) && count($raw)) {
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
        if (! is_null($payload)) {
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
            $error= curl_errno($curl);
            $status = $error ? curl_error($curl) : '';
            if (! $error) {
                break;
            }
        }

        curl_close($curl);

        return [
            'raw'           =>      $raw,
            'info'          =>      $info,
            'error'         =>      $error,
            'status'        =>      $status
        ];
    }
}