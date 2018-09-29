<?php

namespace payment\client;

use payment\ChargeContext;
use payment\Config;
use payment\Common\PayException;
/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 客户端充值类
 * @link      https://github.com/lettellyou/payment
 */

class Charge
{
    /**
     * 支持的支付通道
     * @var array
     */
    private static $supportChannel = [
        Config::IPS_CHANNEL_WEB_GATEWAY,
        Config::IPS_CHANNEL_WEB_QUICK,
        Config::IPS_CHANNEL_WAP_QUICK,

        Config::SAND_CHANNEL_WEB_GATEWAY,
        Config::SAND_CHANNEL_WEB_QUICK,
        Config::SAND_CHANNEL_WAP_QUICK,

        Config::YSE_CHANNEL_WEB_GATEWAY,

        Config::UNION_CHANNEL_WEB_GATEWAY,
        Config::UNION_CHANNEL_WAP_GATEWAY,

        Config::YEE_CHANNEL_WEB_GATEWAY,

        Config::UNS_CHANNEL_WEB_GATEWAY,
    ];

    /**
     * 运行实例
     * @var
     */
    protected static $instance;


    /**
     * 获取支付实例
     * @param $channel
     * @param $config
     * @return ChargeContext
     * @throws PayException
     */
    protected static function getInstance($channel, $config)
    {
        date_default_timezone_set('Asia/Shanghai');
        mb_internal_encoding("UTF-8");
        if(is_null(self::$instance)) {
            static::$instance = new ChargeContext();
        }
        try {
            static::$instance->initCharge($channel, $config);
        } catch (PayException $e) {
            throw $e;
        }
        return static::$instance;
    }

    /**
     * 运行
     * @param $channel
     * @param $config
     * @param $data
     * @return mixed
     * @throws PayException
     */
    public static function run($channel, $config, $data)
    {
        if(!in_array($channel, self::$supportChannel)) {
            throw new PayException('暂不支持该支付通道');
        }
        try {
            $instance = self::getInstance($channel, $config);
            $res = $instance->charge($data);
        } catch (PayException $e) {
            throw $e;
        }
        return $res;
    }
}