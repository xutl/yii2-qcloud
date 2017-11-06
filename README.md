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
"xutl/yii2-qcloud": "~2.0.0"
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
        ],
    ]
];
```

使用
----
 
 v1.0
```php
use xutl\qcloud\Qcloud;

/** var Qcloud $qcloud */
$qcloud = Yii::$app->qcloud;
$wenzhi = $qcloud->createRequest(Qcloud::API_WENZHI,'gz');
$package = [
            'title'=>'啦啦啦啦啦啦',
            "content"=>"操"
        ];
print_r($wenzhi->TextKeywords($package));
```

v2.0 使用方法 QCloud::API_CNS 这个接口名称，可以直接输入字符串，会和主机头组合起来作为Host,第二个参数是可用区，按照说明可为空，
但是有些地区不能为空。

```php
use xutl\qcloud\Qcloud;

/** var Qcloud $qcloud */
$qcloud = Yii::$app->qcloud;
/** @var \yii\httpclient\Response $response */
$response = $qcloud->createRequest(QCloud::API_CNS)->setData(['Action'=>'DomainList',])->send();;
print_r($response->data);
```

v2.0 使用方法 2 ,适用于没有内置的接口，通过此方法来请求。
```php
use xutl\qcloud\Client;

/** var Client $client */
$request = (new Client([
                      'serverHost' => 'cns.api.qcloud.com',
                      'secretId' => '123456',
                      'secretKey' => '654321',
                      'region' => null
                  ]))
                  ->createRequest();
                  
/** @var \yii\httpclient\Response $response */
$response = $request->setMethod('POST')->setData(['Action'=>'DomainList',])->send();;
print_r($response->data);
```

资源
-----

* [公共参数](http://wiki.qcloud.com/wiki/%E5%85%AC%E5%85%B1%E5%8F%82%E6%95%B0)
* [API列表](http://wiki.qcloud.com/wiki/API)
* [错误码](http://wiki.qcloud.com/wiki/%E9%94%99%E8%AF%AF%E7%A0%81)
