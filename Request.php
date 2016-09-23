<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\qcloud;

use Yii;
use yii\base\Object;
use yii\httpclient\Client;

/**
 * Class Request
 * @package xutl\qcloud
 */
class Request extends Object
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
    public $serverHost;

    /**
     * 请求的Uri
     * @var string
     */
    public $serverUri = '/v2/index.php';

    /**
     * 区域参数
     * @var string
     */
    public $defaultRegion = "";

    /**
     * 请求方法
     * @var string
     */
    public $requestMethod = "POST";

    private $_version = 'SDK_PHP_1.0';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->defaultRegion)) {
            $this->defaultRegion = 'gz';
        }
    }

    /**
     * 通过__call转发请求
     * @param  string $name 方法名
     * @param  array $arguments 参数
     * @return
     */
    public function __call($name, $arguments)
    {
        $action = ucfirst($name);
        $params = [];
        if (is_array($arguments) && !empty($arguments)) {
            $params = (array)$arguments[0];
        }
        $params['Action'] = $action;
        if (!isset($params['Region'])){
            $params['Region'] = $this->defaultRegion;
        }
        return $this->_dispatchRequest($name, $params);
    }

    /**
     * 发起接口请求
     * @param  string $name 接口名
     * @param  array $params 接口参数
     * @return
     */
    protected function _dispatchRequest($name, $params)
    {
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
        $plainText = $this->makeSignPlainText($params);
        //签名
        $params['Signature'] = base64_encode(hash_hmac('sha1', $plainText, $this->secretKey, true));

        $response = (new Client)->createRequest()
            ->setUrl('https://' . $this->serverHost . $this->serverUri)
            ->setMethod($this->requestMethod)
            ->setData($params)
            ->send();
        return $response->data;
    }

    /**
     * _buildParamStr
     * 拼接参数
     * @param  array $requestParams 请求参数
     * @param  string $requestMethod 请求方法
     * @return
     */
    protected function _buildParamStr($requestParams)
    {
        $paramStr = '';
        ksort($requestParams);
        $i = 0;
        foreach ($requestParams as $key => $value) {
            if ($key == 'Signature' || ($this->requestMethod == 'POST' && substr($value, 0, 1) == '@')) {
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

        return $paramStr;
    }

    /**
     * 生成拼接签名源文字符串
     * @param  array $requestParams 请求参数
     * @return
     */
    protected function makeSignPlainText($requestParams)
    {
        $url = $this->serverHost . $this->serverUri;
        // 取出所有的参数
        $paramStr = $this->_buildParamStr($requestParams);
        $plainText = $this->requestMethod . $url . $paramStr;
        return $plainText;
    }
}