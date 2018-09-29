<?php
namespace Payment\Client;

use payment\common\PayException;
use payment\Config;
use payment\notify\PayNotifyInterface;
use payment\NotifyContext;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 异步通知的客户端类
 * @link      https://github.com/lettellyou/payment
 */
class Notify
{
    private static $supportChannel = [
        Config::IPS_PAY,// 环讯
        Config::SAND_PAY,// 杉德
        Config::YSE_PAY,// 银盛
        Config::UNION_PAY,// 银联
        Config::YEE_PAY,// 易宝
        Config::UNS_PAY,// 银生宝
    ];

    /**
     * 异步通知类
     * @var NotifyContext
     */
    protected static $instance;

    protected static function getInstance($type, $config)
    {
        date_default_timezone_set('Asia/Shanghai');
        mb_internal_encoding("UTF-8");
        if (is_null(self::$instance)) {
            static::$instance = new NotifyContext();
        }
        try {
            static::$instance->initNotify($type, $config);
        } catch (PayException $e) {
            throw $e;
        }
        return static::$instance;
    }

    /**
     * 执行异步工作
     * @param $type
     * @param $config
     * @param $callback
     * @param array $data
     * @return mixed
     * @throws PayException
     */
    public static function run($type, $config, $callback, $data=[])
    {
        if (! in_array($type, self::$supportChannel)) {
            throw new PayException('暂不支持该异步方式');
        }
        try {
            $config['isCheck'] = false;
            $instance = self::getInstance($type, $config);
            $ret = $instance->notify($callback, $data);
        } catch (PayException $e) {
            throw $e;
        }
        return $ret;
    }

    /**
     * 返回异步通知的数据
     * @param $type
     * @param $config
     * @return array|false
     * @throws PayException
     */
    public static function getNotifyData($type, $config)
    {
        try {
            $instance = self::getInstance($type, $config);
            return $instance->getNotifyData();
        } catch (PayException $e) {
            throw $e;
        }
    }
}
