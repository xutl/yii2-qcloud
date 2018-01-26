# yii2-qcloud

适用于Yii2的[腾讯云](http://www.qcloud.com)API接口类。

1.0.x 和  2.0.x 不兼容，但都是可用的。

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xutl/yii2-qcloud
```

or add

```
"xutl/yii2-qcloud": "~3.0.0"
```

to the require section of your `composer.json` file.

配置
----

To use this extension, you have to configure the Connection class in your application configuration:

```php
return [
    //....
    'components' => [
        'qcloud' => [
            'class' => 'xutl\qcloud\Qcloud',
            'secretId' => 'abcdefg',
            'secretKey' => 'abcdefg',
            'params'=> [//这里是非扩展的配置参数，如队列任务等
                'aaa.appvvvKey' => 123456789
            ],
            'components' => [
               //各子组件配置，如果无需配置不写即可。也可动态注入配置。
               //如果子组件使用独立的 `secretId` 和 `secretKey` 那么在子组件中单独配置即可，如果没有配置默认使用父  `accessId` 和 `accessKey` 。
               //如果你自己扩展了其他的子组件，这里定义下新的组件配置即可，配置方式，数组接口和 YII 原生组件一致！
              //etc
            ]
        ],
    ]
];
```

使用
----

```php
$cdn = Yii::$app->qcloud->cdn;
$response = $cdn->describeCdnHosts();
print_r($response->data);
```

资源
-----

* [公共参数](http://wiki.qcloud.com/wiki/%E5%85%AC%E5%85%B1%E5%8F%82%E6%95%B0)
* [API列表](http://wiki.qcloud.com/wiki/API)
* [错误码](http://wiki.qcloud.com/wiki/%E9%94%99%E8%AF%AF%E7%A0%81)
