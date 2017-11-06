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
     * @var string 服务主机名
     */
    public $serverHost;

    /**
     * 区域参数
     * @var string
     */
    public $region;

    /**
     * @var bool 是否使用安全连接
     */
    public $secureConnection = true;

    /**
     * 请求的Uri
     * @var string
     */
    public $serverUri = '/v2/index.php';

    /**
     * @var string
     */
    protected $signatureMethod = self::SIGNATURE_METHOD_HMAC_SHA256;

    /**
     * @var string 版本号
     */
    private $_version = 'SDK_PHP_1.0';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->serverHost === null) {
            throw new InvalidConfigException('The "serverHost" property must be set.');
        }
        if ($this->secretId === null) {
            throw new InvalidConfigException('The "secretId" property must be set.');
        }
        if ($this->secretKey === null) {
            throw new InvalidConfigException('The "secretKey" property must be set.');
        }
        $this->baseUrl = ($this->secureConnection ? 'https://' : 'http://') . $this->serverHost . $this->serverUri;
        $this->responseConfig['format'] = \yii\httpclient\Client::FORMAT_JSON;
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
        $params['RequestClient'] = $this->_version;
        $params['SignatureMethod'] = $this->signatureMethod;

        ksort($params);
        $url = $this->serverHost . $this->serverUri;
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