<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use yii\di\Instance;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
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
        //$this->requestConfig['format'] = Client::FORMAT_JSON;
        $this->responseConfig['format'] = Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
        //$this->on(Client::EVENT_AFTER_SEND, [$this, 'ResponseEvent']);
    }

    /**
     * 请求事件
     * @param RequestEvent $event
     * @return void
     */
    public function RequestEvent(RequestEvent $event)
    {
        $params = $event->request->getData();
        if (!isset($params['Region']) && !empty($this->region)) {
            $params['Region'] = $this->region;
        }
        if (!isset($params['SecretId'])) {
            $params['SecretId'] = $this->secretId;
        }
        if (!isset($params['Nonce'])) {
            $params['Nonce'] = uniqid();
        }
        if (!isset($params['Timestamp'])) {
            $params['Timestamp'] = time();
        }
        $params['RequestClient'] = 'Yii2_HTTP_CLIENT';
        $params['SignatureMethod'] = $this->signatureMethod;

        ksort($params);
        $url = $this->baseUrl;
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
}