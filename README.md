# [Easy Organization](https://github.com/jucloud/easy-organization)

## 前言

📦 一个 PHP 获取第三方（天眼查、企查查、启信宝）工商信息 SDK，公司业务需求问题，目前只实现了搜索和获取机构基本信息，其余接口未进行对接，大家如有需求可根据需求自行fork或者提交pr。

为了节约实现时间加快业务实现，总体架构借鉴了[yansongda/pay](https://github.com/yansongda/pay)，之前在做支付相关业务时，轮子造的很不错，比较简单容易理解，而且代码优雅，感谢yansongda作出的技术贡献。

欢迎 Star，欢迎 PR！


[![Latest Stable Version](https://poser.pugx.org/jucloud/easy-organization/v/stable.svg)](https://packagist.org/packages/jucloud/easy-organization)
[![Latest Unstable Version](https://poser.pugx.org/jucloud/easy-organization/v/unstable.svg)](https://packagist.org/packages/jucloud/easy-organization)
[![Total Downloads](https://poser.pugx.org/jucloud/easy-organization/downloads)](https://packagist.org/packages/jucloud/easy-organization)
[![License](https://poser.pugx.org/jucloud/easy-organization/license)](https://packagist.org/packages/jucloud/easy-organization)



## 特点

- 多租户支持
- Swoole 支持
- 灵活的插件机制
- 丰富的事件系统
- 根据天眼查、企查查、启信宝最新 API 开发而成
- 文件结构清晰易理解，可以随心所欲添加本项目中未支持的网关
- 方法使用更优雅，不必再去研究那些奇怪的的方法名或者类名是做啥用的
- 符合 PSR2、PSR3、PSR4、PSR7、PSR11、PSR14、PSR18 等各项标准，你可以各种方便的与你的框架集成

## 运行环境
- PHP 7.4+
- composer

## 支持的方法

### 天眼查

- [搜索](https://open.tianyancha.com/open/816)
- [企业基本信息](https://open.tianyancha.com/open/817)
- [特殊企业基本信息](https://open.tianyancha.com/open/1117)
- [企业基本信息（含企业联系方式）](https://open.tianyancha.com/open/818)
- ...

### 企查查

- [企业搜索](https://openapi.qcc.com/dataApi/1027)
- [企业工商模糊搜索](https://openapi.qcc.com/dataApi/886)
- [企业工商照面](https://openapi.qcc.com/dataApi/410)
- ...


### 启信宝

- [高级搜索](https://data.qixin.com/api-detail?categoryId=1309333f837748bbafda78c9d02f40d8&apiId=1.2&from=qxb-c-api)
- [模糊搜索](https://data.qixin.com/api-detail?categoryId=1309333f837748bbafda78c9d02f40d8&apiId=1.31&from=qxb-c-api)
- [工商照面](https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=1.41&from=qxb-c-api)
- [律所基本信息](https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=35.2&from=qxb-c-api)
- [社会组织基本信息](https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=36.2&from=qxb-c-api)
- [香港企业信息](https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=46.1&from=qxb-c-api)
- [事业单位基本信息](https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=47.96&from=qxb-c-api)
- ...

## 安装

```bash
composer require jucloud/easy-organization
```

laravel 扩展包请 [传送至这里](https://github.com/jucloud/laravel-organization)

## 使用示例

基本使用（以天眼查为例）:

```php
<?php

namespace App\Http\Controllers;

use JuCloud\EasyOrganization\EasyOrganization;

class IndexController
{
    protected $config = [
        'tianyancha' => [
            'default' => [
                // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
                'mode' => EasyOrganization::MODE_NORMAL,
                // 必填-天眼查分配的 token
                'token' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
            ],       
        ],   
        'logger' => [ // optional
            'enable' => false,
            'file' => storage_path('logs/jucloud.organization.log'),,
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ];

    public function search()
    {   
        // keyword 关键词
        // page_size 每页大小
        // page_index 当前页数
        $result = EasyOrganization::tianyancha($this->config)->search([
            'keyword' => '',
            'page_size' => 0, //非必填
            'page_index' => 20, //非必填
        ]);

        return $result;
    }

}
```

## 代码贡献

由于测试及使用环境的限制，本项目中只开发了天眼查、企查查、启信宝的部分网关。
如果您有其它网关的需求，或者发现本项目中需要改进的代码，**_欢迎 Fork 并提交 PR！_**

## LICENSE

MIT
