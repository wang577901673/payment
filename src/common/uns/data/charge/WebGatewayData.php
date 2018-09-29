<?php
namespace payment\common\uns\data\charge;

use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银盛web网关支付 接口的数据处理类
 * @link https://github.com/lettellyou/payment
 */

class WebGatewayData extends ChargeBaseData
{
    /**
     * 构建数据
     * @return array|mixed
     */
    protected function getParams()
    {
        $params = [
            "version" => $this->version,
            "merchantId" => $this->merCode,
            "merchantUrl" => $this->notifyUrl,
            "responseMode" => $this->responseMode,
            "orderId" => $this->orderNo,
            "amount" => $this->amount,
            "currencyType" => $this->currencyType,
            "assuredPay" => $this->assuredPay,
            "time" => date('YmdHis'),
            "remark" => $this->attach,
            "bankCode" => strtolower($this->bankCode),
            "b2b" => $this->b2b,
            "frontUrl" => $this->returnUrl,
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