<?php
namespace payment\common\union\data\charge;

use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联wap网关支付 接口的数据处理类
 * @link https://github.com/lettellyou/payment
 */

class WapGatewayData extends ChargeBaseData
{
    /**
     * 构建数据
     * @return array|mixed
     */
    protected function getParams()
    {
        $params = [
            "version" => $this->version,
            "encoding" => $this->encoding,
            "txnType" => $this->txnType,
            "txnSubType" => $this->txnSubType,
            "bizType" => $this->bizType,
            "frontUrl" => $this->returnUrl,
            "backUrl" => $this->notifyUrl,
            "signMethod" => $this->signMethod,
            "channelType" => $this->channelType,
            "accessType" => $this->accessType,
            "currencyCode" => $this->currencyCode,
            "merId" => $this->merCode,
            "orderId" => $this->orderNo,
            "txnTime" => date('YmdHis'),
            "txnAmt" => $this->amount,
            "payTimeout" => date('YmdHis', strtotime('+15 minutes')),
        ];
        return $params;
    }

    public function buildData()
    {
        $result = $this->buildRequestPara($this->getParams());
        if($result['resCode'] == Config::TRADE_SUCCESS_CODE) {
            $html = $this->buildForm($this->postUrl, $result['data']);
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$html,'type'=>Config::IS_FORM];
        } else {
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$result['resMsg']];
        }
    }
}