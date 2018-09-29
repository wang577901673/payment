<?php
namespace payment\charge\ips;

use payment\common\ips\IpsBaseStrategy;
use payment\common\ips\data\charge\WebGatewayData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯web网关支付
 * @link https://github.com/lettellyou/payment
 */

class IpsWebGatewayCharge extends IpsBaseStrategy
{

    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://newpay.ips.com.cn/psfp-entry/gateway/payment.do';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        return WebGatewayData::class;
    }
}