<?php
namespace payment\charge\uns;

use payment\common\uns\UnsBaseStrategy;
use payment\common\uns\data\charge\WebGatewayData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银生宝web网关支付
 * @link https://github.com/lettellyou/payment
 */

class UnsWebGatewayCharge extends UnsBaseStrategy
{

    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://www.unspay.com/unspay/page/linkbank/payRequest.do';

    /**
     * 响应方式
     * 1-页面响应， 2-后台响应， 3-两者都需
     * @var string
     */
    protected $responseMode = '3';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        $this->config->responseMode = $this->responseMode;
        return WebGatewayData::class;
    }
}