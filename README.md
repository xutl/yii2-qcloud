# yii2-qcloud

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xutl/yii2-qcloud
```

or add

```
"xutl/yii2-qcloud": "~1.0.0"
```

to the require section of your `composer.json` file.

Configuration
-------------

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

Usage
-----

The following
single line of code in a view file would render a [JQuery UI DatePicker](http://api.jqueryui.com/datepicker/) widget:

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
