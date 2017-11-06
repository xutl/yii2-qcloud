<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use yii\base\InvalidConfigException;
use yii\httpclient\RequestEvent;

/**
 * Class Client
 * @package xutl\qcloud
 */
class Client extends \yii\httpclient\Client
{
    /**
     * @var string
     */
    public $secretId;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * 区域参数
     * @var string
     */
    public $region;

    private $_version = 'SDK_PHP_1.0';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->baseUrl === null) {
            throw new InvalidConfigException('The "baseUrl" property must be set.');
        }
        if ($this->secretId === null) {
            throw new InvalidConfigException('The "secretId" property must be set.');
        }
        if ($this->secretKey === null) {
            throw new InvalidConfigException('The "secretKey" property must be set.');
        }
        if ($this->region === null) {
            throw new InvalidConfigException('The "region" property must be set.');
        }
        
        $this->responseConfig['format'] = \yii\httpclient\Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, function (RequestEvent $event) {
            $params = $event->request->getData();
            if (!isset($params['Region'])){
                $params['Region'] = $this->region;
            }
            if (!isset($params['SecretId'])) {
                $params['SecretId'] = $this->secretId;
            }
            if (!isset($params['Nonce'])) {
                $params['Nonce'] = rand(1, 65535);
            }
            if (!isset($params['Timestamp'])) {
                $params['Timestamp'] = time();
            }
            $params['RequestClient'] = $this->_version;

            $paramStr = '';
            ksort($requestParams);
            $i = 0;
            foreach ($requestParams as $key => $value) {
                if ($key == 'Signature' || ($event->request->getMethod() == 'POST' && substr($value, 0, 1) == '@')) {
                    continue;
                }
                // 把 参数中的 _ 替换成 .
                if (strpos($key, '_')) {
                    $key = str_replace('_', '.', $key);
                }
                if ($i == 0) {
                    $paramStr .= '?';
                } else {
                    $paramStr .= '&';
                }
                $paramStr .= $key . '=' . $value;
                ++$i;
            }

            $plainText = $event->request->getMethod() . $event->request->getFullUrl() . $paramStr;

            //签名
            $params['Signature'] = base64_encode(hash_hmac('sha1', $plainText, $this->secretKey, true));

            $event->request->setData($params);
        });
    }
}