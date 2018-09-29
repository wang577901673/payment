<?php

namespace payment\common;

use payment\common\PayException;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 配置文件接口，主要提供返回属性数组的功能
 * @link      https://github.com/lettellyou/payment
 */

abstract class ConfigInterface
{

    /**
     * 是否检测配置项
     * @var bool
     */
    public $isCheck = true;

    /**
     * 初始化配置文件
     * ConfigInterface constructor.
     * @param array $config
     * @throws \payment\common\PayException
     */
    final public function __construct(array $config)
    {
        try {
            if(isset($config['isCheck'])) {
                $this->isCheck = $config['isCheck'];
            }
            $this->initConfig($config);
        } catch (PayException $e) {
            throw $e;
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * 初始化配置文件具体实现
     * @param array $config
     * @return mixed
     */
    abstract public function initConfig(array $config);
}