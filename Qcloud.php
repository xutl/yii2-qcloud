<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\qcloud;

use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Qcloud
 * @package xutl\qcloud
 */
class Qcloud extends Component
{
    /**
     * 用户账户
     */
    const API_ACCOUNT = 'account';

    /**
     * 账单
     */
    const API_BILL = 'bill';

    /**
     * 黑石BM
     */
    const API_BM = 'bm';

    /**
     * 云硬盘
     */
    const API_CBS = 'cbs';

    /**
     * CDB数据库
     */
    const API_CDB = 'cdb';

    /**
     * CDN
     */
    const API_CDN = 'cdn';

    /**
     * 云缓存
     */
    const API_CMEM = 'cmem';

    /**
     * 云解析
     */
    const API_CNS = 'cns';

    /**
     * 云服务器
     */
    const API_CVM = 'cvm';

    /**
     * 弹性公网Ip
     */
    const API_EIP = 'eip';

    /**
     * 镜像
     */
    const API_IMAGE = 'image';

    /**
     * 负载均衡
     */
    const API_LB = 'lb';

    /**
     * 直播
     */
    const API_LIVE = 'live';

    /**
     * MARKET
     */
    const API_MARKET = 'market';

    /**
     * 云监控
     */
    const API_MONITOR = 'monitor';

    /**
     * 弹性伸缩
     */
    const API_SCALING = 'scaling';

    /**
     * 云安全
     */
    const API_SEC = 'sec';

    /**
     * 快照
     */
    const API_SNAPSHOT = 'snapshot';

    /**
     * 云数据库TDSQL
     */
    const API_TDSQL = 'tdsql';

    /**
     * 产品售卖
     */
    const API_TRADE = 'trade';

    /**
     * 视频云
     */
    const API_VOD = 'vod';

    /**
     * VPC
     */
    const API_VPC = 'vpc';

    /**
     * 文智
     */
    const API_WENZHI = 'wenzhi';

    /**
     * 云搜
     */
    const API_YUNSOU = 'yunsou';

    /**
     * @var string
     */
    public $secretId;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * 请求的Uri
     * @var string
     */
    public $serverUri = '/v2/index.php';

    /**
     * 请求方法
     * @var string
     */
    public $requestMethod = "POST";

    /**
     * 服务器地址
     * @var array
     */
    protected $_serverHosts = [
        'account' => 'account.api.qcloud.com',
        'bill' => 'bill.api.qcloud.com',
        'bm' => 'bm.api.qcloud.com',
        'cbs' => 'cbs.api.qcloud.com',
        'cdb' => 'cdb.api.qcloud.com',
        'cdn' => 'cdn.api.qcloud.com',
        'cmem' => 'cmem.api.qcloud.com',
        'cns' => 'cns.api.qcloud.com',
        'cvm' => 'cvm.api.qcloud.com',
        'eip' => 'eip.api.qcloud.com',
        'image' => 'image.api.qcloud.com',
        'lb' => 'lb.api.qcloud.com',
        'live' => 'live.api.qcloud.com',
        'market' => 'market.api.qcloud.com',
        'monitor' => 'monitor.api.qcloud.com',
        'scaling' => 'scaling.api.qcloud.com',
        'sec' => 'csec.api.qcloud.com',
        'snapshot' => 'snapshot.api.qcloud.com',
        'tdsql' => 'tdsql.api.qcloud.com',
        'trade' => 'trade.api.qcloud.com',
        'vod' => 'vod.api.qcloud.com',
        'vpc' => 'vpc.api.qcloud.com',
        'wenzhi' => 'wenzhi.api.qcloud.com',
        'yunsou' => 'yunsou.api.qcloud.com',
    ];

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
     * 获取服务器主机名
     * @param string $service
     * @return mixed|null
     */
    protected function getServerHost($service)
    {
        return isset($this->_serverHosts[$service]) ? $this->_serverHosts[$service] : null;
    }

    /**
     * 创建API请求
     * @param string $service 服务名称
     * @param string|null $region 区域名称
     * @return Request
     */
    public function createRequest($service, $region = null)
    {
        $serverHost = $this->getServerHost($service);
        return new Request([
            'secretId' => $this->secretId,
            'secretKey' => $this->secretKey,
            'defaultRegion' => $region,
            'requestMethod' => $this->requestMethod,
            'serverUri' => $this->serverUri,
            'serverHost' => $serverHost
        ]);
    }
}