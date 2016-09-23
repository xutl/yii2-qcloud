<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\qcloud;

use yii\base\Component;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;

/**
 * Class Qcloud
 * @package xutl\qcloud
 */
class Qcloud extends Component
{
    /**
     * MODULE_ACCOUNT
     * 用户账户
     */
    const MODULE_ACCOUNT = 'account';

    /**
     * MODULE_CVM
     * 云服务器
     */
    const MODULE_CVM = 'cvm';

    /**
     * MODULE_CDB
     * CDB数据库
     */
    const MODULE_CDB = 'cdb';

    /**
     * MODULE_LB
     * 负载均衡
     */
    const MODULE_LB = 'lb';

    /**
     * MODULE_TRADE
     * 产品售卖
     */
    const MODULE_TRADE = 'trade';

    /**
     * MODULE_BILL
     * 账单
     */
    const MODULE_BILL = 'bill';

    /**
     * MODULE_SEC
     * 云安全
     */
    const MODULE_SEC = 'sec';

    /**
     * MODULE_IMAGE
     * 镜像
     */
    const MODULE_IMAGE = 'image';

    /**
     * MODULE_MONITOR
     * 云监控
     */
    const MODULE_MONITOR = 'monitor';

    /**
     * MODULE_CDN
     * CDN
     */
    const MODULE_CDN = 'cdn';

    /**
     * MODULE_VPC
     * VPC
     */
    const MODULE_VPC = 'vpc';

    /**
     * MODULE_VOD
     * VOD
     */
    const MODULE_VOD = 'vod';

    /**
     * YUNSOU
     */
    const MODULE_YUNSOU = 'yunsou';

    /**
     * cns
     */
    const MODULE_CNS = 'cns';

    /**
     * wenzhi
     */
    const MODULE_WENZHI = 'wenzhi';

    /**
     * MARKET
     */
    const MODULE_MARKET = 'market';

    /**
     * MODULE_EIP
     * 弹性公网Ip
     */
    const MODULE_EIP = 'eip';

    /**
     * MODULE_LIVE
     * 直播
     */
    const MODULE_LIVE = 'live';

    /**
     * MODULE_SNAPSHOT
     * 快照
     */
    const MODULE_SNAPSHOT = 'snapshot';

    /**
     * MODULE_CBS
     * 云硬盘
     */
    const MODULE_CBS = 'cbs';

    /**
     * MODULE_SCALING
     * 弹性伸缩
     */
    const MODULE_SCALING = 'scaling';

    /**
     * MODULE_CMEM
     * 云缓存
     */
    const MODULE_CMEM = 'cmem';

    /**
     * MODULE_TDSQL
     * 云数据库TDSQL
     */
    const MODULE_TDSQL = 'tdsql';

    /**
     * MODULE_BM
     * 黑石BM
     */
    const MODULE_BM = 'bm';

    /**
     * @var string
     */
    public $_secretId;

    /**
     * @var string
     */
    public $_secretKey;

    /**
     * 请求的Uri
     * @var string
     */
    public $_serverUri = '/v2/index.php';

    /**
     * 区域参数
     * @var string
     */
    public $_defaultRegion = "";

    /**
     * 请求方法
     * @var string
     */
    public $_requestMethod = "POST";

    /**
     * @var array
     */
    protected $_serverHosts = [
        'account' => 'account.api.qcloud.com',
        'bill' => 'bill.api.qcloud.com',
        'wenzhi' => 'wenzhi.api.qcloud.com',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->_secretId === null) {
            throw new InvalidConfigException('The "secretId" property must be set.');
        }
        if ($this->_secretKey === null) {
            throw new InvalidConfigException('The "secretKey" property must be set.');
        }
        if ($this->_defaultRegion === null) {
            throw new InvalidConfigException('The "defaultRegion" property must be set.');
        }
    }

    /**
     * 获取服务器主机名
     * @param string $service
     * @return mixed|null
     */
    protected function getServerHost($service)
    {
        return isset($this->_serverHosts[$service]) ? $this->_serverHosts[$service] : null;
    }

    /**
     * @param $service
     * @return Client
     */
    public function load($service)
    {
        $serverHost = $this->getServerHost($service);
        return new Request([
            'secretId' => $this->_secretId,
            'secretKey' => $this->_secretKey,
            'defaultRegion' => $this->_defaultRegion,
            'requestMethod' => $this->_requestMethod,
            'serverUri' => $this->_serverUri,
            'serverHost' => $serverHost
        ]);
    }
}