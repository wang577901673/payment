<?php
namespace payment\notify;

use payment\common\PayException;
use payment\Config;
use payment\utils\ArrayUtil;
use payment\utils\Rsa2Encrypt;
use payment\utils\RsaEncrypt;
use payment\common\ips\IpsConfig;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯回调通知
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
class IpsNotify extends NotifyStrategy
{
    /**
     * IpsNotify constructor.
     * @param array $config
     * @throws PayException
     */
    public function __construct(array $config)
    {
        try {
            $this->config = new IpsConfig($config);
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 获取并格式化处理通知的数据，获取数据失败返回false
     * @param array $model
     * @return array|bool|mixed
     */
    public function getNotifyData($model = [])
    {
        $data = empty($model) ? $_POST : $model;
        if (empty($data)) {
            return false;
        }
        return $data;
    }

    /**
     * 检查异步通知的数据是否合法
     * 如果检查失败，返回false
     * @param array $data   由 $this->getNotifyData() 返回的数据
     * @return bool|mixed
     * @throws \Exception
     */
    public function checkNotifyData($data)
    {
        $paymentResult = $data['paymentResult'];
        $xmlResult = new \SimpleXMLElement($paymentResult);
        if($xmlResult->GateWayRsp->head->RspCode == "000000") {
            $status = $this->getTradeStatus($xmlResult->GateWayRsp->body->Status);
            if ($status !== Config::TRADE_SUCCESS_CODE) {
                // 如果不是交易成功状态，直接返回错误，
                return false;
            }
            // 检查签名
            $flag = $this->verifySign($data);
        } else {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 向客户端返回必要的数据
     * @param  $data
     * @return mixed|
     */
    public function getRetData($data)
    {
        $paymentResult = $data['paymentResult'];
        $xmlResult = new \SimpleXMLElement($paymentResult);
        $xmljson= json_encode($xmlResult );//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);//将json转换成数组
        $xmlarray['channel'] = Config::IPS_PAY;
        $xmlarray['GateWayRsp']['body']['Amount'] = floatval($xmlarray['GateWayRsp']['body']['Amount'])*100;//将金额转为分
        return $xmlarray;
    }

    /**
     * @param bool $flag
     * @param string $msg
     * @return array|mixed
     */
    protected function replyNotify($flag, $msg = '')
    {
        if ($flag) {
            return ['rspCode'=>1, 'rspMsg'=>$msg, 'callMsg'=>'ipscheckok'];
        } else {
            return ['rspCode'=>0, 'rspMsg'=>$msg, 'callMsg'=>'ipscheckfail'];
        }
    }

    /**
     * 返回统一的交易状态
     * @param $status
     * @return string
     * @author helei
     */
    protected function getTradeStatus($status)
    {
        if($status == 'Y') {
            return Config::TRADE_SUCCESS_CODE;
        } else if($status == 'P') {
            return Config::TRADE_DELL_CODE;
        } else {
            return Config::TRADE_FAIL_CODE;
        }
    }

    /**
     * 检查数据 签名是否被篡改
     * @param $data
     * @return bool
     */
    protected function verifySign($data)
    {
        $paymentResult = $data['paymentResult'];
        $xmlResult = new \SimpleXMLElement($paymentResult);
        $strSignature = $xmlResult->GateWayRsp->head->Signature;
        $strBody = $this->subStrXml("<body>","</body>",$paymentResult);
        $notifyStr = $strBody.$this->config->merCode.$this->config->merKey;
        if($strSignature == md5($notifyStr)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * php截取<body>和</body>之間字符串
     * @param string $begin 开始字符串
     * @param string $end 结束字符串
     * @param string $str 需要截取的字符串
     * @return string
     */
    protected function subStrXml($begin,$end,$str){
        $b= (strpos($str,$begin));
        $c= (strpos($str,$end));
        return substr($str,$b,$c-$b + 7);
    }
}
