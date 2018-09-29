<?php
namespace payment\common\ips\data\charge;

use payment\common\ips\data\IpsBaseData;
use payment\Config;
use payment\common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯支付数据封装类
 * @link https://github.com/lettellyou/payment
 */

abstract class ChargeBaseData extends IpsBaseData
{
    protected function checkReqData()
    {
        if (empty($this->data['orderNo'])) {
            throw new PayException('订单号不能为空，并且为10到64位的唯一字符串');
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
        $this->data['amount'] = floatval($this->data['amount']/100);
    }

    /**
     * 生成要请求给IPS的参数XMl
     * @param $data
     * @return array
     */
    protected function buildRequestPara($data) {
        $sReqXml = "<Ips>";
        $sReqXml .= "<GateWayReq>";
        $sReqXml .= $this->buildHead($data);
        $sReqXml .= $this->buildBody($data);
        $sReqXml .= "</GateWayReq>";
        $sReqXml .= "</Ips>";
        //写日志，记录请求报文...
        return ['resCode'=>Config::TRADE_SUCCESS_CODE, 'resMsg'=>Config::TRADE_SUCCESS, 'data'=>$sReqXml];
    }

    /**
     * 请求报文头
     * @param $data 请求前的参数数组
     * @return 要请求的报文头
     */
    protected function buildHead($data){
        $sReqXmlHead = "<head>";
        $sReqXmlHead .= "<Version>".$data["Version"]."</Version>";
        $sReqXmlHead .= "<MerCode>".$data["MerCode"]."</MerCode>";
        $sReqXmlHead .= "<MerName>".$data["MerName"]."</MerName>";
        $sReqXmlHead .= "<Account>".$data["Account"]."</Account>";
        $sReqXmlHead .= "<MsgId>".$data["MsgId"]."</MsgId>";
        $sReqXmlHead .= "<ReqDate>".$data["ReqDate"]."</ReqDate>";
        $sReqXmlHead .= "<Signature>".$this->makeSign($this->buildSignData($data))."</Signature>";
        $sReqXmlHead .= "</head>";
        return $sReqXmlHead;
    }

    /**
     * 请求报文体
     * @param $data 请求前的参数数组
     * @return string
     */
    protected function buildBody($data){
        $sReqXmlBody = "<body>";
        $sReqXmlBody .= "<MerBillNo>".$data["MerBillNo"]."</MerBillNo>";
        $sReqXmlBody .= "<GatewayType>".$data["PayType"]."</GatewayType>";
        $sReqXmlBody .= "<Date>".$data["Date"]."</Date>";
        $sReqXmlBody .= "<CurrencyType>".$data["CurrencyType"]."</CurrencyType>";
        $sReqXmlBody .= "<Amount>".$data["Amount"]."</Amount>";
        $sReqXmlBody .= "<Lang>".$data["Lang"]."</Lang>";
        $sReqXmlBody .= "<Merchanturl><![CDATA[".$data["Return_url"]."]]></Merchanturl>";
        $sReqXmlBody .= "<FailUrl><![CDATA[".$data["FailUrl"]."]]></FailUrl>";
        $sReqXmlBody .= "<Attach><![CDATA[".$data["Attach"]."]]></Attach>";
        $sReqXmlBody .= "<OrderEncodeType>".$data["OrderEncodeType"]."</OrderEncodeType>";
        $sReqXmlBody .= "<RetEncodeType>".$data["RetEncodeType"]."</RetEncodeType>";
        $sReqXmlBody .= "<RetType>".$data["RetType"]."</RetType>";
        $sReqXmlBody .= "<ServerUrl><![CDATA[".$data["S2Snotify_url"]."]]></ServerUrl>";
        $sReqXmlBody .= "<BillEXP>".$data["BillEXP"]."</BillEXP>";
        $sReqXmlBody .= "<GoodsName>".$data["GoodsName"]."</GoodsName>";
        $sReqXmlBody .= "<IsCredit>".$data["IsCredit"]."</IsCredit>";
        $sReqXmlBody .= "<BankCode>".$data["BankCode"]."</BankCode>";
        $sReqXmlBody .= "<ProductType>".$data["ProductType"]."</ProductType>";
        $sReqXmlBody .= "</body>";
        return $sReqXmlBody;
    }

    /**
     * 构建签名数据字符串
     * @param $data
     * @return mixed|string
     */
    public function buildSignData($data)
    {
        return $this->buildBody($data).$this->data["merCode"].$this->data["merKey"];
    }


}