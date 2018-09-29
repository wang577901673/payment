<?php
namespace payment\charge\sand;

use payment\common\sand\SandBaseStrategy;
use payment\common\sand\data\charge\WebQuickData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德web快捷支付
 * @link https://github.com/lettellyou/payment
 */

class SandWebQuickCharge extends SandBaseStrategy
{
    /**
     * 接口地址
     * @var string
     */
    protected $postUrl = 'https://cashier.sandpay.com.cn/gateway/api/order/pay';

    /**
     * 接口名称
     * @var string
     */
    protected $method = 'sandpay.trade.pay';

    /**
     * 支付模式
     * bank_pc-银行网关支付
     * sand_h5-杉德H5银行卡支付
     * @var string
     */
    protected $payMode = 'sand_h5';

    /**
     * 产品编码
     * @var string
     */
    protected $productId = '00000008';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        $this->config->method = $this->method;
        $this->config->payMode = $this->payMode;
        $this->config->productId = $this->productId;
        return WebQuickData::class;
    }
}