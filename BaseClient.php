<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;

/**
 * Class BaseClient
 * @package xutl\qcloud
 */
class BaseClient extends Component
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
     * @var string
     */
    public $baseUrl;

    /**
     * 区域参数
     * @var string
     */
    public $region = "gz";

    private $_version = 'SDK_PHP_1.0';

    /**
     * @var Client internal HTTP client.
     */
    private $_httpClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->secretId === null) {
            throw new InvalidConfigException('The "secretId" property must be set.');
        }
        if ($this->secretKey === null) {
            throw new InvalidConfigException('The "secretKey" property must be set.');
        }
    }

    /**
     * Returns HTTP client.
     * @return Client internal HTTP client.
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->baseUrl,
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
            $this->_httpClient->on(Client::EVENT_BEFORE_SEND, function (RequestEvent $event) {
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
        return $this->_httpClient;
    }
}