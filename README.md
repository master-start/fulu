## 安装要求
php 7.3以上

## 安装方式
```bash
composer require kangdev/fulu
```

## 使用方式
示例：
```php
<?php

//此config配置可全局封装
$config = [
    'AppKey'      => 'your-app_key',
    'AppSecret'   => 'your-app_secret',
    'MemberCode'  => 'your-member_code',
    'online'      => 0, //0=沙箱环境，1=线上环境
    'debug' => true,
    'log' => [
        'name' => 'fulu',
        'file' => __DIR__ . '/fulu.log',
        'level' => 'debug',
        'permission' => 0777,
    ],
];
$server = new \Kangdev\Fulu\FuLuServer($config);


// 获取商品列表接口 其中post为请求方式
$server->postData([
    'method' => 'fulu.goods.list.get',
    'product_name' => "腾讯Q币"
],'json');

// 获取商品信息接口
$server->postData([
    'method' => 'fulu.goods.info.get',
    'product_id' => '10000001',
    'detail_format' => 1,
]);

```

## 注意事项
- 当online=0(沙箱环境)时，AppKey、AppSecret、MemberCode非必需；
- 当online=1(生产环境)时，正式环境相关参数为必需项。
- method 为接口方法名称，其余的为请求参数，公共请求参数已封装无需公共参数。
- 请求方式有：json、post、get、upload 一般默认为json，其他格式可能接口不支持
