<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;

/**
 * Class ApiGateway
 * @package xutl\qcloud
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class ApiGateway extends Client
{
    const SIGNATURE_METHOD_HMAC_SHA1 = 'hmac-sha1';
    const SIGNATURE_METHOD_HMAC_SHA256 = 'hmac-sha256';

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
    protected $signatureMethod = self::SIGNATURE_METHOD_HMAC_SHA1;

    /**
     * @var string
     */
    protected $dateTimeFormat = 'D, d M Y H:i:s \G\M\T';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
        if (empty ($this->secretId)) {
            throw new InvalidConfigException ('The "secretId" property must be set.');
        }
        if (empty ($this->secretKey)) {
            throw new InvalidConfigException ('The "secretKey" property must be set.');
        }
        $this->responseConfig['format'] = Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
    }

    /**
     * 请求事件
     * @param RequestEvent $event
     * @return void
     * @throws Exception
     */
    public function RequestEvent(RequestEvent $event)
    {
        $headers = [];
        $headers['Date'] = gmdate($this->dateTimeFormat);
        $headers['Nonce'] = uniqid();
        $signString = "date: " . $headers['Date'] . "\n" . "nonce: " . $headers['Nonce'];
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMAC_SHA256) {
            $sign = base64_encode(hash_hmac('sha256', $signString, $this->secretKey, true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMAC_SHA1) {
            $sign = base64_encode(hash_hmac('sha1', $signString, $this->secretKey, true));
        } else {
            throw new Exception('Unsupported signature method.');
        }
        $headers['Authorization'] = "hmac id=\"{$this->secretId}\", algorithm=\"{$this->signatureMethod}\", headers=\"date nonce\", signature=\"{$sign}\"";
        $event->request->addHeaders($headers);
    }

    /**
     * 发送请求
     * @param string $method
     * @param array|string $url
     * @param array|string $data
     * @param array $headers
     * @param array $options
     * @return array response data.
     * @throws Exception
     */
    public function sendRequest($method, $url, $data, $headers, $options)
    {
        $request = $this->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->addHeaders($headers)
            ->addOptions($options);
        if (is_array($data)) {
            $request->setData($data);
        } else {
            $request->setContent($data);
        }
        $response = $request->send();
        if (!$response->isOk) {
            throw new Exception('Request fail. response: ' . $response->content, $response->statusCode);
        }
        return $response->data;
    }
}