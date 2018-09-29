<?php
namespace payment\notify;

use payment\common\PayException;
use payment\Config;
use payment\utils\ArrayUtil;
use payment\utils\Rsa2Encrypt;
use payment\utils\RsaEncrypt;
use payment\common\yse\YseConfig;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银盛回调通知
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
class YseNotify extends NotifyStrategy
{
    /**
     * YseNotify constructor.
     * @param array $config
     * @throws PayException
     */
    public function __construct(array $config)
    {
        try {
            $this->config = new YseConfig($config);
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
        $result = $data;
        $status = $this->getTradeStatus($result['trade_status']);
        if ($status !== Config::TRADE_SUCCESS_CODE) {
            // 如果不是交易成功状态，直接返回错误，
            return false;
        }
        // 检查签名
        $flag = $this->verifySign($data);
        return $flag;
    }

    /**
     * 向客户端返回必要的数据
     * @param  $data
     * @return mixed|
     */
    public function getRetData($data)
    {
        $result = $data;
        $result['channel'] = Config::YSE_PAY;
        $result['total_amount'] = floatval($result['total_amount'])*100;
        return $result;
    }

    /**
     * @param bool $flag
     * @param string $msg
     * @return array|mixed
     */
    protected function replyNotify($flag, $msg = '')
    {
        if ($flag) {
            return ['rspCode'=>1, 'rspMsg'=>$msg, 'callMsg'=>'success'];
        } else {
            return ['rspCode'=>0, 'rspMsg'=>$msg, 'callMsg'=>''];
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
        if($status == 'TRADE_SUCCESS') {
            return Config::TRADE_SUCCESS_CODE;
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
        $sign = trim($data['sign']);
        unset($data['sign']);
        ksort($data);
        $str = '';
        foreach ($data as $key => $val) {
            if ($val) $str .= $key . '=' . $val . '&';
        }
        $data = trim($str, '&');
        $certificateCAcerContent = file_get_contents($this->config->publicKey);
        $certificateCApemContent = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
        $success = openssl_verify($data, base64_decode($sign), openssl_get_publickey($certificateCApemContent), OPENSSL_ALGO_SHA1);
        return $success;
    }
}
