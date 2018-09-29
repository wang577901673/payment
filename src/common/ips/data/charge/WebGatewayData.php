<?php
namespace payment\common\ips\data\charge;

use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯web网关支付 接口的数据处理类
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
            "Version" => $this->version,
            "MerCode" => $this->merCode,
            "Account" => $this->account,
            "MerCert" => $this->merKey,
            "PostUrl" => $this->postUrl,
            "S2Snotify_url" => $this->notifyUrl,
            "Return_url" => $this->returnUrl,
            "CurrencyType" => $this->ccy,
            "OrderEncodeType" => $this->orderEncodeType,
            "RetType" => $this->retType,
            "MerBillNo" => $this->orderNo,    //商户订单号
            "MerName" => $this->merName,      //商户名
            "MsgId" => $this->orderNo,
            "Lang" => $this->lang,
            "PayType" => $this->payType,      //支付方式 01#借记卡 02#信用卡 03#IPS账户支付
            "FailUrl" => $this->failUrl,
            "Date" => date('Ymd',$this->addTime),         //订单日期
            "ReqDate" => date("YmdHis"),
            "Amount" => $this->amount,      //订单金额
            "Attach" => $this->attach,        //数字、字母或数字+字母,自定义
            "RetEncodeType" => $this->retEncodeType,
            "BillEXP" => $this->billEXP,
            "GoodsName" => $this->title,
            "BankCode" => $this->bankCode,    //直连必填，银行编号
            "IsCredit" => $this->isCredit,
            "ProductType" => $this->productType,
        ];
        return $params;
    }

    public function buildData()
    {
        $result = $this->buildRequestPara($this->getParams());
        if($result['resCode'] == Config::TRADE_SUCCESS_CODE) {
            $html = $this->buildForm($this->postUrl, ['pGateWayReq'=>$result['data']]);
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$html,'type'=>Config::IS_FORM];
        } else {
            return ['resCode'=>$result['resCode'],'resMsg'=>$result['resMsg'],'html'=>$result['resMsg']];
        }
        //$data = ['pGateWayReq'=>$this->buildRequestPara($this->getParams())];
        //return $this->buildForm($this->postUrl, $data);
    }
}