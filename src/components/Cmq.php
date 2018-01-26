<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace xutl\qcloud\components;

use xutl\qcloud\BaseClient;
use yii\base\InvalidConfigException;

/**
 * Class Cmq
 * @package xutl\qcloud\components
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Cmq extends BaseClient
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
    }
}