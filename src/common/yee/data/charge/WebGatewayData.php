<?php
namespace payment\common\yee\data\charge;

use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 易宝web网关支付 接口的数据处理类
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
            "p0_Cmd" => $this->cmd,
            "p1_MerId" => $this->merCode,
            "p2_Order" => $this->orderNo,
            "p3_Amt" => $this->amount,
            "p4_Cur" => $this->cur,
            "p5_Pid" => $this->title,
            "p6_Pcat" => $this->productCat,
            "p7_Pdesc" => $this->body,
            "p8_Url" => $this->returnUrl,
            "p9_SAF" => $this->address,
            "pa_MP" => time().str_pad($this->orderNo,6,"0",STR_PAD_LEFT),
            "pb_ServerNotifyUrl" => $this->notifyUrl,
            "pd_FrpId" => '',
            "pm_Period" => '7',
            "pn_Unit" => 'day',
            "pr_NeedResponse" => $this->prNeedResponse,
            "pt_UserName" => '',
            "pt_PostalCode" => '',
            "pt_Address" => '',
            "pt_TeleNo" => '',
            "pt_Mobile" => '',
            "pt_Email" => '',
            "pt_LeaveMessage" => '',
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