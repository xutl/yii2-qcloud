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
 * Class QCloud
 * @package xutl\qcloud
 */
class QCloud extends Component
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
     * @var bool 是否使用安全连接
     */
    public $secureConnection = true;

    /**
     * 请求的Uri
     * @var string
     */
    public $serverUri = '/v2/index.php';

    public $defaultRegion = null;

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
     * 创建API请求
     * @param string $service 服务名称
     * @param string|null $region 区域名称
     * @return \yii\httpclient\Request
     */
    public function createRequest($service, $region = null)
    {
        $serverHost = $service . '.api.qcloud.com';
        return (new BaseClient([
            'serverHost' => $serverHost,
            'secretId' => $this->secretId,
            'secretKey' => $this->secretKey,
            'secureConnection' => $this->secureConnection,
            'serverUri' => $this->serverUri,
            'region' => $region ? $region : $this->defaultRegion
        ]))->createRequest()->setMethod('POST');
    }


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
}