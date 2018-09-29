<?php
namespace payment\notify;

use payment\common\PayException;
use payment\Config;
use payment\utils\ArrayUtil;
use payment\utils\Rsa2Encrypt;
use payment\utils\RsaEncrypt;
use payment\common\yee\YeeConfig;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 易宝回调通知
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
class YeeNotify extends NotifyStrategy
{
    /**
     * YeeNotify constructor.
     * @param array $config
     * @throws PayException
     */
    public function __construct(array $config)
    {
        try {
            $this->config = new YeeConfig($config);
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
        $data = empty($model) ? $_REQUEST : $model;
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
        $status = $this->getTradeStatus($result['r1_Code']);
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
        $result['channel'] = Config::YEE_PAY;
        $result['r3_Amt'] = floatval($result['r3_Amt'])*100;
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
            return ['rspCode'=>1, 'rspMsg'=>$msg, 'callMsg'=>'SUCCESS'];
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
        if($status == '1') {
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
        $v_hmac_safe = $data['hmac_safe'];
        $v_hmac = $data['hmac'];
        $param = array(
            'p1_MerId' => $data['p1_MerId'],
            'r0_Cmd' => $data['r0_Cmd'],
            'r1_Code' => $data['r1_Code'],
            'r2_TrxId' => $data['r2_TrxId'],
            'r3_Amt' => $data['r3_Amt'],
            'r4_Cur' => $data['r4_Cur'],
            'r5_Pid' => $data['r5_Pid'],
            'r6_Order' => $data['r6_Order'],
            'r7_Uid' => $data['r7_Uid'],
            'r8_MP' => $data['r8_MP'],
            'r9_BType' => $data['r9_BType'],
            'hmac' => $data['hmac'],
            'hmac_safe' => $data['hmac_safe'],
        );
        $hmacSafe = $this->getHMacSafe($param);
        $hmacLocal = $this->hMacLocal($param);
        if(($hmacSafe == $v_hmac_safe) && ($hmacLocal == $v_hmac)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成本地的安全签名数据
     * @param $data
     * @return string
     */
    protected function getHMacSafe($data)
    {
        $str = '';
        foreach ($data as $k=> $v) {
            if( $k!="hmac" && $k!="hmac_safe" && $v !=null)
            {
                $str .=  $v."#" ;
            }
        }
        $str = rtrim( trim($str), '#' );
        return $this->hMacMd5($str);
    }

    /**
     * 生成本地签名hmac
     * @param $data
     * @return string
     */
    protected function hMacLocal($data)
    {
        $str = '';
        foreach ($data as $k=> $v) {
            if($k!="hmac" && $k!="hmac_safe")
            {
                $str .= $v;
            }
        }
        return $this->hMacMd5($str);
    }

    /**
     * 生产hmac
     * @param $data
     * @return string
     */
    protected function hMacMd5($data)
    {
        $key = iconv("GBK","UTF-8",$this->config->merKey);
        $data = iconv("GBK","UTF-8",$data);
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*",md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;
        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }
}
