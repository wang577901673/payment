<?php
namespace payment\common\yee\data\charge;

use payment\common\yee\data\YeeBaseData;
use payment\Config;
use payment\common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 易宝支付数据封装类
 * @link https://github.com/lettellyou/payment
 */

abstract class ChargeBaseData extends YeeBaseData
{
    protected function checkReqData()
    {
        if (empty($this->data['orderNo'])) {
            throw new PayException('订单号不能为空');
        }
        if (bccomp($this->data['amount'], Config::PAY_MIN_FEE, 2) === -1) {
            throw new PayException('支付金额不能低于 '. Config::PAY_MIN_FEE . ' 分');
        }
        if (empty($this->data['title'])) {
            $this->data['title'] = 'recharge';
        }
        if (empty($this->data['body'])) {
            $this->data['body'] = 'recharge';
        }
        if (empty($this->data['attach'])) {
            $this->data['attach'] = '用户充值';
        }
        $this->data['amount'] = floatval($this->data['amount']/100);
    }


    /**
     * 构建请求数据
     * @param $data
     * @return array
     */
    protected function buildRequestPara($data) {
        $sign = $this->makeSign($data);
        $data['hmac'] = $sign;
        //写日志，记录请求报文...
        return ['resCode'=>Config::TRADE_SUCCESS_CODE, 'resMsg'=>Config::TRADE_SUCCESS, 'data'=>$data];
    }

    /**
     * 构建签名数据字符串
     * @param $data
     * @return mixed|string
     */
    public function buildSignData($data)
    {
        return implode($data);
    }


}