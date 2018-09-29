<?php
namespace payment\charge\ips;

use payment\common\ips\IpsBaseStrategy;
use payment\common\ips\data\charge\WapQuickData;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯wap快捷支付
 * @link https://github.com/lettellyou/payment
 */

class IpsWapQuickCharge extends IpsBaseStrategy
{
    /**
     * 接口请求地址
     * @var string
     */
    protected $postUrl = 'https://mobilegw.ips.com.cn/psfp-mgw/paymenth5.do';

    public function getBuildDataClass()
    {
        $this->config->postUrl = $this->postUrl;
        return WapQuickData::class;
    }
}