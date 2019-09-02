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

use GuzzleHttp\Client;
use ShugaChara\Core\Traits\Singleton;
use ShugaChara\Music\AbstractInterfaces\MusicInterface;

/**
 * Class API
 * @method static $this getInstance(...$args)
 * @package ShugaChara\Music
 */
class Music implements MusicInterface
{
    use Singleton;

    /**
     * @var 平台
     */
    protected $platforms = API::SITES;

    protected $guzzleOptions = [];

    public function __construct()
    {

    }

    /**
     * 搜索音乐
     *
     * @param        $keyword
     * @param string $platform      null 所有平台, 也可以指定平台
     * @return array|mixed
     * @throws SGCException
     */
    public function search($keyword, $platform = null)
    {
        // TODO: Implement search() method.

        $songAll = [];

        $platforms = $platform ? [$platform] : $this->platforms;

        foreach ($platforms as $platform) {
            $apiServer = API::getInstance()->setSite($platform);

            try {
                $songs = json_decode($apiServer->setFormat(true)->search($keyword), true);
            } catch (\Exception $exception) {
                continue;
            }

            foreach ($songs as $key => &$song) {
                $detail = json_decode($apiServer->setFormat(true)->playurl(array_get($song, 'playurl_id')), true);
                if (array_get($detail, 'url')) {
                    $song = array_merge($song, $detail);
                } else {
                    unset($songs[$key]);
                }
            }

            $songAll = array_merge($songAll, array_values($songs));
        }

        return $songAll;
    }

    public function format(array $song, $keyword)
    {
        // TODO: Implement format() method.
    }

    public function formatAll(array $songs, $keyword)
    {
        // TODO: Implement formatAll() method.
    }

    /**
     * 歌曲下载
     *
     * @param array $song
     * @param null  $downloadsDir
     * @return mixed|void
     * @throws \Exception
     */
    public function download(array $song, $downloadsDir = null)
    {
        // TODO: Implement download() method.

        try {
            $downloadsDir = $downloadsDir ? : $this->getDownloadsDir();
            $this->getHttpClient()->get($song['url'], ['save_to' => $downloadsDir . implode(',', $song['artist']) . ' - ' . $song['name'] . '.mp3']);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 获取下载存储目录
     *
     * @return string
     */
    private function getDownloadsDir()
    {
        $downloadsDir = PATH_SEPARATOR === ':' ? trim(exec('cd ~; pwd')) . '/Downloads/' : 'C:\\Users\\' . get_current_user() . '\\Downloads\\';

        if (!is_dir($downloadsDir) && !mkdir($downloadsDir, 0777, true) && !is_dir($downloadsDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $downloadsDir));
        }

        return $downloadsDir;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * @param array $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }
}