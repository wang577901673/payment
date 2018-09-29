<?php
namespace payment\charge\union;

use payment\common\union\UnionBaseStrategy;
use payment\common\union\data\charge\WebGatewayData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联web网关支付
 * @link https://github.com/lettellyou/payment
 */

class UnionWebGatewayCharge extends UnionBaseStrategy
{

    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://gateway.95516.com/gateway/api/frontTransReq.do';

    /**
     * 渠道类型 07-PC，08-手机
     * @var string
     */
    protected $channelType = '07';

    /**
     * 业务类型
     * B2C 网关支付
     * @var string
     */
    protected $bizType = '000201';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        $this->config->channelType = $this->channelType;
        $this->config->bizType = $this->bizType;
        return WebGatewayData::class;
    }
}