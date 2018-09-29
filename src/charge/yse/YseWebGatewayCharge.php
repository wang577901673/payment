<?php
namespace payment\charge\yse;

use payment\common\yse\YseBaseStrategy;
use payment\common\yse\data\charge\WebGatewayData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银盛web网关支付
 * @link https://github.com/lettellyou/payment
 */

class YseWebGatewayCharge extends YseBaseStrategy
{

    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://openapi.ysepay.com/gateway.do';

    /**
     * 接口名称
     * @var string
     */
    protected $method = 'ysepay.online.directpay.createbyuser';

    /**
     * 订单超时时间
     * 取值范围：3d～15d
     * m-分钟，h-小时，d-天
     * @var string
     */
    protected $timeoutExpress = '1d';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        $this->config->method = $this->method;
        $this->config->timeoutExpress = $this->timeoutExpress;
        return WebGatewayData::class;
    }
}