<?php
namespace payment;

use payment\notify\IpsNotify;
use payment\notify\SandNotify;
use payment\notify\YseNotify;
use payment\notify\UnionNotify;
use payment\notify\YeeNotify;
use payment\notify\UnsNotify;
use payment\notify\NotifyStrategy;
use payment\Common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 异步通知环境类
 * @link https://github.com/lettellyou/payment
* @property string $subject
 */

class NotifyContext
{
    /**
     * 支付渠道
     * @var BaseStrategy
     */
    protected $channel;

    /**
     * 获取具体支付通道
     * @param $channel
     * @param array $config
     * @throws PayException
     */
    public function initNotify($channel, array $config)
    {
        try {
            switch ($channel) {
                case Config::IPS_PAY:
                    $this->channel = new IpsNotify($config);
                    break;
                case Config::SAND_PAY:
                    $this->channel = new SandNotify($config);
                    break;
                case Config::YSE_PAY:
                    $this->channel = new YseNotify($config);
                    break;
                case Config::UNION_PAY:
                    $this->channel = new UnionNotify($config);
                    break;
                case Config::YEE_PAY:
                    $this->channel = new YeeNotify($config);
                    break;
                case Config::UNS_PAY:
                    $this->channel = new UnsNotify($config);
                    break;
                default:
                    throw new PayException('暂不支持该渠道');
                    break;
            }
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * @param $callback
     * @param $data
     * @return mixed
     * @throws PayException
     */
    public function notify($callback, $data)
    {
        if(!$this->channel instanceof NotifyStrategy) {
            throw new PayException('请检查初始化是否正确');
        }
        try {
            return $this->channel->handle($callback, $data);
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 返回异步通知的数据
     * @return array|false
     */
    public function getNotifyData()
    {
        if($this->channel->config->returnRaw) {
            return $this->channel->getNotifyData();
        } else {
            return $this->channel->getRetData($this->channel->getNotifyData());
        }
    }
}