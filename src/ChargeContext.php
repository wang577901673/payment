<?php
namespace payment;

use payment\charge\ips\IpsWebGatewayCharge;
use payment\charge\ips\IpsWebQuickCharge;
use payment\charge\ips\IpsWapQuickCharge;
use payment\charge\sand\SandWebGatewayCharge;
use payment\charge\sand\SandWebQuickCharge;
use payment\charge\sand\SandWapQuickCharge;
use payment\charge\yse\YseWebGatewayCharge;
use payment\charge\union\UnionWebGatewayCharge;
use payment\charge\yee\YeeWebGatewayCharge;
use payment\charge\uns\UnsWebGatewayCharge;
use payment\common\BaseStrategy;
use payment\Config;
use payment\Common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环境类
 * @link https://github.com/lettellyou/payment
* @property string $subject
 */

class ChargeContext
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
    public function initCharge($channel, array $config)
    {
        try {
            switch ($channel) {
                case Config::IPS_CHANNEL_WEB_GATEWAY:
                    $this->channel = new IpsWebGatewayCharge($config);
                    break;
                case Config::IPS_CHANNEL_WEB_QUICK:
                    $this->channel = new IpsWebQuickCharge($config);
                    break;
                case Config::IPS_CHANNEL_WAP_QUICK:
                    $this->channel = new IpsWapQuickCharge($config);
                    break;
                case Config::SAND_CHANNEL_WEB_GATEWAY:
                    $this->channel = new SandWebGatewayCharge($config);
                    break;
                case Config::SAND_CHANNEL_WEB_QUICK:
                    $this->channel = new SandWebQuickCharge($config);
                    break;
                case Config::SAND_CHANNEL_WAP_QUICK:
                    $this->channel = new SandWapQuickCharge($config);
                    break;
                case Config::YSE_CHANNEL_WEB_GATEWAY:
                    $this->channel = new YseWebGatewayCharge($config);
                    break;
                case Config::UNION_CHANNEL_WEB_GATEWAY:
                    $this->channel = new UnionWebGatewayCharge($config);
                    break;
                case Config::UNION_CHANNEL_WAP_GATEWAY:
                    $this->channel = new UnionWapGatewayCharge($config);
                    break;
                case Config::YEE_CHANNEL_WEB_GATEWAY:
                    $this->channel = new YeeWebGatewayCharge($config);
                    break;
                case Config::UNS_CHANNEL_WEB_GATEWAY:
                    $this->channel = new UnsWebGatewayCharge($config);
                    break;
                default:
                    throw new PayException('暂不支持该渠道');
                    break;
            }
        } catch (PayException $e) {
            throw $e;
        }
    }

    public function charge(array $data)
    {
        if(!$this->channel instanceof BaseStrategy) {
            throw new PayException('请检查初始化是否正确');
        }

        try {
            return $this->channel->handle($data);
        } catch (PayException $e) {
            throw $e;
        }
    }
}