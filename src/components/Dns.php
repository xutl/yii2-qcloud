<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud\components;

use xutl\qcloud\BaseClient;

/**
 * 云解析接口
 *
 * 域名相关接口
 * @method domainCreate(array $params) 添加域名
 * @method setDomainStatus(array $params) 设置域名状态
 * @method domainList(array $params) 获取域名列表
 * @method domainDelete(array $params) 删除域名
 *
 * 解析记录相关接口
 * @method recordCreate(array $params) 添加解析记录
 * @method recordStatus(array $params) 设置解析记录状态
 * @method recordModify(array $params) 修改解析记录
 * @method recordList(array $params) 获取解析记录列表
 * @method recordDelete(array $params) 删除解析记录
 *
 * @package xutl\qcloud\components
 * @see https://cloud.tencent.com/document/api/302/4032
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Dns extends BaseClient
{

    public $baseUrl = 'https://cns.api.qcloud.com/v2/index.php';
}