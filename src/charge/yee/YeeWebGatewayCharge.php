<?php
namespace payment\charge\yee;

use payment\common\yee\YeeBaseStrategy;
use payment\common\yee\data\charge\WebGatewayData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 易宝web网关支付
 * @link https://github.com/lettellyou/payment
 */

class YeeWebGatewayCharge extends YeeBaseStrategy
{

    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://www.yeepay.com/app-merchant-proxy/node';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        return WebGatewayData::class;
    }
}