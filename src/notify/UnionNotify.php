<?php
namespace payment\notify;

use payment\common\PayException;
use payment\Config;
use payment\utils\ArrayUtil;
use payment\utils\Rsa2Encrypt;
use payment\utils\RsaEncrypt;
use payment\common\union\UnionConfig;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联回调通知
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
class UnionNotify extends NotifyStrategy
{
    /**
     * UnionNotify constructor.
     * @param array $config
     * @throws PayException
     */
    public function __construct(array $config)
    {
        try {
            $this->config = new UnionConfig($config);
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
     */
    public function checkNotifyData($data)
    {
        if($data['respMsg'] == "成功[0000000]") {
            $status = $this->getTradeStatus($data['respCode']);
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
        $result = $data;
        $result['channel'] = Config::UNION_PAY;
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
            return ['rspCode'=>1, 'rspMsg'=>$msg, 'callMsg'=>'200'];
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
        if($status == '00') {
            return Config::TRADE_SUCCESS_CODE;
        } else {
            return Config::TRADE_FAIL_CODE;
        }
    }

    /**
     * 检查数据 签名是否被篡改
     * @param $data
     * @return bool|int
     */
    protected function verifySign($data)
    {
        $signature_str = $data['signature'];
        unset ( $data ['signature'] );
        $params_str = $this->createLinkString ($data);
        if($data['version']=='5.1.0'){
            $strCert = $data['signPubKeyCert'];
            /* ****************/
            openssl_x509_read($strCert);
            $certInfo = openssl_x509_parse($strCert);
            $cn = $this->getIdentitiesFromCertficate($certInfo);
            $isCn = false;//验证验签证书的CN
            if($isCn){
                if ($this->config->company != $cn){
                    $strCert = null;
                }
            } else if ($this->config->company != $cn && "00040000:SIGN" != $cn){
                $strCert = null;
            }
            $from = date_create ( '@' . $certInfo ['validFrom_time_t'] );
            $to = date_create ( '@' . $certInfo ['validTo_time_t'] );
            $now = date_create ( date ( 'Ymd' ) );
            $interval1 = $from->diff ( $now );
            $interval2 = $now->diff ( $to );
            if ($interval1->invert || $interval2->invert) {
                new PayException("signPubKeyCert has expired");
                $strCert = null;
            }
            $result = openssl_x509_checkpurpose($strCert, X509_PURPOSE_ANY, [$this->config->rootCertPath,$this->config->publicKey]);
            if($result === FALSE){
                new PayException("alidate signPubKeyCert by rootCert failed");
                $strCert = null;
            } else if($result === TRUE){

            } else {
                new PayException("validate signPubKeyCert by rootCert failed with error");
                $strCert = null;
            }

            /* ****************/
            if($strCert == null){
                new PayException("validate cert err: " . $data["signPubKeyCert"]);
                $isSuccess = false;
            } else {
                $params_sha256x16 = hash('sha256', $params_str);
                $signature = base64_decode ( $signature_str );
                $isSuccess = openssl_verify ( $params_sha256x16, $signature,$strCert, "sha256" );
            }
        } else {
            $isSuccess = false;
        }
        return $isSuccess;
    }

    /**
     * @param $params
     * @return bool|string
     */
    public function createLinkString($params)
    {
        $linkString = "";
        ksort ( $params );
        foreach ($params as $key=> $value) {
            $linkString .= $key . "=" . $value . "&";
        }
        $linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );
        return $linkString;
    }

    /**
     * 验证验签证书的CN
     * @param $certInfo
     * @return null
     */
    public function getIdentitiesFromCertficate($certInfo)
    {
        $cn = $certInfo['subject'];
        $cn = $cn['CN'];
        $company = explode('@',$cn);
        if(count($company) < 3) {
            return null;
        }
        return $company[2];
    }
}
