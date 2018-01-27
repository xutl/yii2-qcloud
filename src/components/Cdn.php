<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud\components;

use xutl\qcloud\BaseClient;

/**
 * Class Cdn
 * @package xutl\qcloud\components
 *
 * 域名管理
 * @method addCdnHost(array $params) 添加域名至 CDN
 * @method onlineHost(array $params) 启动域名加速服务
 * @method offlineHost(array $params) 关闭域名加速服务
 * @method deleteCdnHost(array $params) 删除加速域名
 *
 * 配置管理
 * @method updateCdnConfig(array $params) 修改域名配置
 * @method setHttpsInfo(array $params) HTTPS配置
 * @method updateCdnProject(array $params) 修改域名所属项目
 *
 * 配置查询
 * @method describeCdnHosts(array $params) 域名配置查询
 * @method getHostInfoByHost(array $params) 指定域名查询配置
 * @method getHostInfoById(array $params) 指定域名ID查询配置
 *
 * 数据查询
 * @method describeCdnHostInfo(array $params) 汇总统计查询
 * @method getCdnHostsDetailStatistics(array $params) 消耗明细查询
 * @method getCdnOriginStat(array $params) 回源统计明细查询
 * @method getCdnStatTop(array $params) TOP 100 URL查询
 * @method getCdnProvIspDetailStat(array $params) 指定省份、运营商带宽明细查询
 *
 * 刷新预热
 * @method getCdnRefreshLog(array $params) 查询提交的刷新任务执行状态
 * @method refreshCdnUrl(array $params) 提交URL刷新
 * @method refreshCdnDir(array $params) 提交目录刷新
 * @method cdnPusherV2(array $params) 提交URL预热（内测中）
 * @method getPushLogs(array $params) 查询提交的预热任务执行状态（内测中）
 * @method flushOrPushOverall(array $params) 提交URL同时刷新国内&海外CDN资源
 *
 * 日志查询
 * @method generateLogList(array $params) 根据用户输入的域名 ID（仅支持一个），查询该域名一个月内每天的日志下载链接
 * @method getCdnLogList(array $params) 根据用户输入的域名，查询指定时间区间的日志下载链接
 *
 * @see https://cloud.tencent.com/document/api/228/1722
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Cdn extends BaseClient
{
    public $baseUrl = 'https://cdn.api.qcloud.com/v2/index.php';
}