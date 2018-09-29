<?php
namespace payment\common\sand\data\charge;

use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德wap快捷支付 接口的数据处理类
 * @link https://github.com/lettellyou/payment
 */

class WapQuickData extends ChargeBaseData
{
    /**
     * 构建数据
     * @return array|mixed
     */
    protected function getParams()
    {
        $params = [
            "version" => $this->version,
            "method" => $this->method,
            "productId" => $this->productId,
            "accessType" => $this->accessType,
            "merCode" => $this->merCode,
            "channelType" => $this->channelType,
            "reqTime" => date("YmdHis"),
            "orderNo" => $this->orderNo,
            "amount" => $this->amount,
            "subject" => $this->title,
            "body" => $this->body,
            'txnTimeOut' => date('YmdHis', time()+3600),
            'payMode' => $this->payMode,
            'payExtra' => json_encode(array('payType'=> $this->payType,'bankCode' => $this->bankCode)),
            "clientIp" => $this->clientIp,
            "notifyUrl" => $this->notifyUrl,
            "returnUrl" => $this->returnUrl,
            "attach" => $this->attach,
        ];
        return $params;
    }

    public function buildData()
    {
        $result = $this->buildRequestPara($this->getParams());
        if($result['resCode'] == Config::TRADE_SUCCESS_CODE) {
            $html = $this->buildForm($result['data']['submitUrl'], $result['data']['params']);
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$html,'type'=>Config::IS_FORM];
        } else {
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$result['resMsg']];
        }
    }
}