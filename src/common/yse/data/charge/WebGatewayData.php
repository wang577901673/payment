<?php
namespace payment\common\yse\data\charge;

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
            "business_code" => $this->businessCode,
            "charset" => $this->charset,
            "method" => $this->method,
            "notify_url" => $this->notifyUrl,
            "out_trade_no" => $this->orderNo,
            "partner_id" => $this->merCode,
            "return_url" => $this->returnUrl,
            "seller_id" => $this->sellerId,
            "seller_name" => $this->sellerName,
            "sign_type" => $this->signType,
            "subject" => $this->title,
            "timeout_express" => $this->timeoutExpress,
            "timestamp" => date('Y-m-d H:i:s'),
            "total_amount" => number_format($this->amount,2),
            "version" => $this->version,
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