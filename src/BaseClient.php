<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use yii\di\Instance;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;

/**
 * Class Client
 * @package xutl\qcloud
 */
class BaseClient extends Client
{
    const SIGNATURE_METHOD_HMAC_SHA1 = 'HmacSHA1';
    const SIGNATURE_METHOD_HMAC_SHA256 = 'HmacSHA256';

    /**
     * @var string
     */
    public $secretId;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * @var string 操作的地域
     */
    public $region;

    /**
     * @var string
     */
    protected $signatureMethod = self::SIGNATURE_METHOD_HMAC_SHA256;

    /**
     * @var string|QCloud
     */
    public $qcloud = 'qcloud';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->qcloud = Instance::ensure($this->qcloud, Qcloud::className());
        if (empty ($this->secretId)) {
            $this->secretId = $this->qcloud->secretId;
        }
        if (empty ($this->secretKey)) {
            $this->secretKey = $this->qcloud->secretKey;
        }
        $this->responseConfig['format'] = Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
    }

    /**
     * 请求事件
     * @param RequestEvent $event
     * @return void
     */
    public function RequestEvent(RequestEvent $event)
    {
        $params = $event->request->getData();
        if (!empty($this->region) && !isset($params['Region'])) {
            $params['Region'] = $this->region;
        }
        $params['SecretId'] = $this->secretId;
        $params['Nonce'] = uniqid();
        $params['Timestamp'] = time();
        $params['RequestClient'] = 'Yii2_HTTP_CLIENT';
        $params['SignatureMethod'] = $this->signatureMethod;
        ksort($params);
        $url = str_replace(['http://', 'https://'], '', $event->request->getFullUrl());
        $i = 0;
        foreach ($params as $key => $val) {
            if ($key == 'Signature' || ($event->request->getMethod() == 'POST' && substr($val, 0, 1) == '@')) {
                continue;
            }
            // 把 参数中的 _ 替换成 .
            if (strpos($key, '_')) {
                $key = str_replace('_', '.', $key);
            }
            $url .= ($i == 0) ? '?' : '&';
            $url .= $key . '=' . $val;
            ++$i;
        }
        $plainText = $event->request->getMethod() . $url;
        //签名
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMAC_SHA256) {
            $params['Signature'] = base64_encode(hash_hmac('sha256', $plainText, $this->secretKey, true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMAC_SHA1) {
            $params['Signature'] = base64_encode(hash_hmac('sha1', $plainText, $this->secretKey, true));
        }

        $event->request->setData($params);
    }

    /**
     * 通过__call转发请求
     * @param string $name 方法名
     * @param array $arguments 参数
     * @return array
     */
    public function __call($name, $arguments)
    {
        $action = ucfirst($name);
        $params = [];
        if (is_array($arguments) && !empty($arguments)) {
            $params = (array)$arguments[0];
        }
        $params['Action'] = $action;
        return $this->_dispatchRequest($params);
    }

    /**
     * 发起接口请求
     * @param array $params 接口参数
     * @return array
     */
    protected function _dispatchRequest($params)
    {
        $response = $this->createRequest()
            ->setMethod('POST')
            ->setData($params)
            ->send();
        return $response->data;
    }
}