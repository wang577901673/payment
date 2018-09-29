<?php
namespace payment\charge\sand;

use payment\common\sand\SandBaseStrategy;
use payment\common\sand\data\charge\WapQuickData;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德wap快捷支付
 * @link https://github.com/lettellyou/payment
 */

class SandWapQuickCharge extends SandBaseStrategy
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
     * 渠道类型 07-互联网 08-移动端
     * @var string
     */
    protected $channelType = '08';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        $this->config->method = $this->method;
        $this->config->payMode = $this->payMode;
        $this->config->channelType = $this->channelType;
        return WapQuickData::class;
    }
}