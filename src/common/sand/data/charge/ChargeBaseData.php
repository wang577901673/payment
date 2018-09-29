<?php
namespace payment\common\sand\data\charge;

use payment\common\PayException;
use payment\common\sand\data\SandBaseData;
use payment\Config;
use payment\utils\Help;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德支付数据封装类
 * @link https://github.com/lettellyou/payment
 */

abstract class ChargeBaseData extends SandBaseData
{
    protected function checkReqData()
    {
        if (empty($this->data['orderNo'])) {
            throw new PayException('订单号不能为空，并且为10到64位的唯一字符串');
        }
        if (bccomp($this->data['amount'], Config::PAY_MIN_FEE, 2) === -1) {
            throw new PayException('支付金额不能低于 '. Config::PAY_MIN_FEE . ' 分');
        }
        if (empty($this->data['clientIp'])) {
            $this->data['clientIp'] = Help::getClientIp();
        }
        if (empty($this->data['title'])) {
            $this->data['title'] = '用户充值';
        }
        if (empty($this->data['body'])) {
            $this->data['body'] = '用户充值';
        }
        if (empty($this->data['attach'])) {
            $this->data['attach'] = '用户充值';
        }
        $this->data['amount'] = str_pad($this->data['amount'],12,"0",STR_PAD_LEFT);
    }

    /**
     * @param $data
     * @return array|mixed
     * @throws PayException
     */
    protected function buildRequestPara($data) {
        $params = array(
            'head' => array(
                'version' => $data['version'],
                'method' => $data['method'],
                'productId' => $data['productId'],
                'accessType' => $data['accessType'],
                'mid' => $data['merCode'],
                'channelType' => $data['channelType'],
                'reqTime' => $data['reqTime']
            ),
            'body' => array(
                'orderCode' => $data['orderNo'],
                'totalAmount' => $data['amount'],
                'subject' => $data['subject'],
                'body' => $data['body'],
                'txnTimeOut' => $data['txnTimeOut'],
                'payMode' => $data['payMode'],
                'payExtra' => $data['payExtra'],
                'clientIp' => $data['clientIp'],
                'notifyUrl' => $data['notifyUrl'],
                'frontUrl' => $data['returnUrl'],
                'extend' => $data['attach']
            )
        );
        $sign = $this->makeSign($params);
        $post = array(
            'charset' => 'utf-8',
            'signType' => '01',
            'data' => json_encode($params),
            'sign' => $sign
        );
        $result = $this->curlReq($this->postUrl, $post);
        $msg = $this->dellData($result);
        return $msg;
        //写日志，记录请求报文...
    }

    /**
     * 处理同步返回的数据
     * @param $result
     * @return array
     * @throws PayException
     */
    protected function dellData($result)
    {
        $arr = array();
        $response = urldecode($result);
        $arrStr = explode('&', $response);
        foreach ($arrStr as $str) {
            $p = strpos($str, "=");
            $key = substr($str, 0, $p);
            $value = substr($str, $p + 1);
            $arr[$key] = $value;
        }
        //验签
        try {
            $this->verify($arr['data'], $arr['sign'],$this->publicKey);
        } catch (PayException $e) {
            throw new PayException($e->getMessage());
        }
        $data = json_decode($arr['data'],true);
        if ($data['head']['respCode']  == "000000" ) {
            $credential = json_decode($data['body']['credential'],true);
            $resCode = Config::TRADE_SUCCESS_CODE;
            $resMsg = Config::TRADE_SUCCESS;
        } else {
            $credential = $data;
            $resMsg = $data['head']['respMsg'];
            $resCode = Config::TRADE_FAIL_CODE;
        }
        $msg = array(
            'resCode'=>$resCode,
            'resMsg'=>$resMsg,
            'data'=>$credential
        );
        return $msg;
    }

    /**
     * 构建签名数据字符串
     * @param $data
     * @return mixed|string
     * @throws \payment\common\PayException
     */
    public function buildSignData($data)
    {
        $this->data['publicKey'] = $this->loadX509Cert($this->data['publicKey']);
        $this->data['privateKey'] = $this->loadPk12Cert($this->data['privateKey'], $this->data['privateKeyPass']);
        return $data;
    }


}