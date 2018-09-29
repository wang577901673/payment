<?php
namespace payment\common\union\data\charge;

use payment\common\union\data\UnionBaseData;
use payment\Config;
use payment\common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联支付数据封装类
 * @link https://github.com/lettellyou/payment
 */

abstract class ChargeBaseData extends UnionBaseData
{
    protected function checkReqData()
    {
        if (empty($this->data['orderNo']) || mb_strlen($this->data['orderNo'],'utf-8') > 16) {
            throw new PayException('订单号不能为空，该参数支持汉字，最大长度为16个');
        }
        if (bccomp($this->data['amount'], Config::PAY_MIN_FEE, 2) === -1) {
            throw new PayException('支付金额不能低于 '. Config::PAY_MIN_FEE . ' 分');
        }
        if (empty($this->data['title'])) {
            $this->data['title'] = '用户充值';
        }
        if (empty($this->data['attach'])) {
            $this->data['attach'] = '用户充值';
        }
        $this->data['amount'] = floatval($this->data['amount']);
    }

    /**
     * 构建请求数据
     * @param $data
     * @return mixed
     * @throws PayException
     */
    protected function buildRequestPara($data) {
        $info = $this->makeSign($data);
        $data = array_merge($data, $info);
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
        ksort($data);
        $signStr = '';
        foreach ($data as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        return rtrim($signStr, '&');
    }


}