# [音乐] MUSIC-SDK 

[![GitHub release](https://img.shields.io/github/release/shugachara/music-sdk.svg)](https://github.com/shugachara/music-sdk/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

## 说明

经常情况下(特别是个人博客类网站) 需要使用音乐播放,本作者就是其中一个,所以在此开发音乐SDK,方便使用。

目前只开发完QQ音乐接口，后续会开发网易云、虾米等平台。

我将会一直维护该项目, 如果有需要改进的地方，欢迎 issue (包括Bug/建议等)。但数据调用的是各网站的 API 接口，有的接口并不是开放的，随时可能失效，或许不能及时更新最新可用代码，本项目相关代码仅供参考。

## 包地址

[Music-SDK](https://packagist.org/packages/shugachara/music-sdk)

## 使用方法

**安装**

```
composer require shugachara/music-sdk
```

**调用**

```php
<?php
namespace App\Http\Controllers;

use ShugaChara\MusicSDK\Services as MusicServices;

class IndexController extends Controller
{
    public function index()
    {
       return MusicServices::getInstance()->setPlatform('QQ')->getResources()->getSinger();
    }
}
```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

## 免责声明

1. 本站音频文件来自各网站接口，本站不会修改任何音频文件
2. 音频版权来自各网站，本站只提供数据查询服务，不提供任何音频存储和贩卖服务

## 开源协议

The MIT License (MIT)