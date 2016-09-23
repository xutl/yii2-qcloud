<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\qcloud;

use yii\base\Object;
use yii\httpclient\Client;

/**
 * Class Request
 * @package xutl\qcloud
 */
class Request extends Object
{

    protected function sendRequest(){
        $client = new Client;

    }

    protected function Sign(){

    }
}