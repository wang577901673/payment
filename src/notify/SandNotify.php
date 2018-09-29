<?php
namespace payment\notify;

use payment\common\PayException;
use payment\Config;
use payment\utils\ArrayUtil;
use payment\utils\Rsa2Encrypt;
use payment\utils\RsaEncrypt;
use payment\common\sand\SandConfig;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 杉德回调通知
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
class SandNotify extends NotifyStrategy
{
    /**
     * SandNotify constructor.
     * @param array $config
     * @throws PayException
     */
    public function __construct(array $config)
    {
        try {
            $this->config = new SandConfig($config);
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
        $result = json_decode(stripslashes($data['data']), true);
        if($result['head']['respCode'] == "000000") {
            $status = $this->getTradeStatus($result['body']['orderStatus']);
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
        $result = json_decode(stripslashes($data['data']), true);
        $result['channel'] = Config::SAND_PAY;
        $result['body']['totalAmount'] = intval($result['body']['totalAmount']);
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
            return ['rspCode'=>1, 'rspMsg'=>$msg, 'callMsg'=>'respCode=000000'];
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
        if($status == 1) {
            return Config::TRADE_SUCCESS_CODE;
        } else {
            return Config::TRADE_FAIL_CODE;
        }
    }

    /**
     * 检查数据 签名是否被篡改
     * @param $data
     * @return bool
     * @throws PayException
     */
    protected function verifySign($data)
    {
        $json = stripslashes($data['data']);
        $publicKey = openssl_pkey_get_public($this->loadX509Cert($this->config->publicKey));
        $verify = openssl_verify($json,base64_decode($data['sign']),$publicKey);
        openssl_free_key($publicKey);
        if($verify) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取公钥
     * @param $path  绝对路径
     * @return mixed
     * @throws PayException
     */
    protected function loadX509Cert($path)
    {
        try {
            $file = file_get_contents($path);
            if(!$file){
                throw new PayException('loadx509Cert::file_get_contents ERROR');
            }
            $cert = chunk_split(base64_encode($file),64,"\n");
            $cert = "-----BEGIN CERTIFICATE-----\n".$cert."-----END CERTIFICATE-----\n";
            $res = openssl_pkey_get_public($cert);
            $detail = openssl_pkey_get_details($res);
            openssl_free_key($res);
            if(!$detail){
                throw new PayException('loadX509Cert::openssl_pkey_get_details ERROR');
            }
            return $detail['key'];
        } catch(PayException $e){
            throw $e;
        }
    }
}
