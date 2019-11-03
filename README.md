# MUSIC - 网易云、 腾讯QQ音乐、百度、酷狗、虾米等平台接口

[![GitHub release](https://img.shields.io/github/release/shugachara/music.svg)](https://github.com/shugachara/music/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

## 说明

经常情况下(特别是个人博客类网站) 需要使用音乐播放,本作者就是其中一个,所以在此开发音乐SDK,方便使用。

目前已开放网易云、 腾讯QQ音乐、百度、酷狗、虾米等平台接口。

我将会一直维护该项目, 如果有需要改进的地方，欢迎 issue (包括Bug/建议等)。但数据调用的是各网站的 API 接口，有的接口并不是开放的，随时可能失效，或许不能及时更新最新可用代码，本项目相关代码仅供参考。

## 包地址

[Music](https://packagist.org/packages/shugachara/music)

## 使用方法

**安装**

```
composer require shugachara/music
```

**调用**

```php
<?php
namespace App\Http\Controllers;

use ShugaChara\Music\API;

// 直接调用底层API
$api = API::getInstance()->setSite('xiami')->setFormat(true);
dump($api->search('生而为人'));
$api->setFormat(false);
dump($api->artist('2110488326'));

// 全网 搜索/下载
$music = Music::getInstance()->search('生而为人');
dump($music);
dd(Music::getInstance()->download($music[0]));
```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

## 免责声明

1. 本站音频文件来自各网站接口，本站不会修改任何音频文件
2. 音频版权来自各网站，本站只提供数据查询服务，不提供任何音频存储和贩卖服务

### 贡献

非常欢迎感兴趣，并且愿意参与其中，共同打造更好PHP生态。

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/shugachara/music/issues)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT