<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace xutl\qcloud\components;


use xutl\qcloud\BaseClient;

/**
 * Class LB
 *
 * 通用负载均衡相关接口
 * @method inquiryLBPrice(array $params) 查询负载均衡实例的价格
 * @method createLoadBalancer(array $params) 通过该接口来购买负载均衡
 * @method describeLoadBalancers(array $params) 查询负载均衡实例的列表
 * @method deleteLoadBalancers(array $params) 删除负载均衡实例
 * @method describeLoadBalancersTaskResult(array $params) 查询负载均衡异步操作接口的执行结果
 * @method getCertListWithLoadBalancer(array $params) 查询证书关联的负载均衡信息
 * @method describeLoadBalancerLog(array $params) 查询负载均衡应用层日志
 * @method getMonitorData(array $params) 查询负载均衡的监控数据
 * @method replaceCert(array $params) 更换负载均衡使用的证书
 *
 *
 * @package xutl\qcloud\components
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class LB extends BaseClient
{
    public $baseUrl = 'https://lb.api.qcloud.com/v2/index.php';
}