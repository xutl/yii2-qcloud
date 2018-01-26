<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\qcloud;

use yii\di\ServiceLocator;
use yii\base\InvalidConfigException;
use xutl\qcloud\components\Cdn;
use xutl\qcloud\components\MQ;
use xutl\qcloud\components\LB;

/**
 * Class QCloud
 * @property Cdn $cdn CDN操控
 * @property MQ $mq CMQ操控
 * @package xutl\qcloud
 */
class QCloud extends ServiceLocator
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
     * @var array qcloud parameters (name => value).
     */
    public $params = [];

    /**
     * Tim constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->preInit($config);
        parent::__construct($config);
    }

    /**
     * 预处理组件
     * @param array $config
     */
    public function preInit(&$config)
    {
        // merge core components with custom components
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

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
     * @return Cdn|object
     * @throws InvalidConfigException
     */
    public function getCdn()
    {
        return $this->get('cdn');
    }

    /**
     * @return MQ|object
     * @throws InvalidConfigException
     */
    public function getMq()
    {
        return $this->get('mq');
    }

    /**
     * @return LB|object
     * @throws InvalidConfigException
     */
    public function getLb()
    {
        return $this->get('lb');
    }

    /**
     * Returns the configuration of qcloud components.
     * @see set()
     */
    public function coreComponents()
    {
        return [
            'cdn' => ['class' => 'xutl\qcloud\components\Cdn'],
            'mq' => ['class' => 'xutl\qcloud\components\MQ'],
            'lb' => ['class' => 'xutl\qcloud\components\LB'],
        ];
    }
}