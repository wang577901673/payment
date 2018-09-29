<?php

use payment\notify\PayNotifyInterface;
use payment\common\PayException;
use payment\client\Notify;
use payment\Config;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 异步通知类
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */

class NotifyClass implements PayNotifyInterface
{
    /**
     * 环讯支付异步通知
     * @return array
     */
    public function ips()
    {
        $callback = new $this();
        $ipsConfig = [
            'merCode'=>'207892',
            'account'=>'',
            'merKey'=>'',
            //'returnRaw'=>true,
        ];
        try {
            //$retData = Notify::getNotifyData(Config::IPS_PAY, $ipsConfig);// 获取第三方的数据，未进行签名检查，returnRaw为true时返回原始的数据
            $ret = Notify::run(Config::IPS_PAY, $ipsConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    /**
     * 杉德支付异步通知
     * @return array
     */
    public function sand()
    {
        $callback = new $this();
        $sandConfig = [
            'merCode'=>'',
            'publicKey'=>__DIR__. '/sand/cert/public.cer',
        ];
        try {
            $ret = Notify::run(Config::SAND_PAY, $sandConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    /**
     * @return mixed
     */
    public function union()
    {
        $callback = new $this();
        $unionConfig = [
            'merCode'=>'',
            'publicKey'=>__DIR__. '/union/cert/public.cer',//公钥，绝对物理路径
            'rootCertPath'=>__DIR__. '/union/cert/rootCert.cer',//跟证书，绝对物理路径
        ];
        try {
            $ret = Notify::run(Config::UNION_PAY, $unionConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    public function uns()
    {
        $callback = new $this();
        $unsConfig = [
            'merCode'=>'',
            'merKey'=>'',
        ];
        try {
            $ret = Notify::run(Config::UNS_PAY, $unsConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    public function yse()
    {
        $callback = new $this();
        $yseConfig = [
            'merCode'=>'',
            'publicKey'=>__DIR__. '/yse/cert/public.cer',//公钥，绝对物理路径
        ];
        try {
            $ret = Notify::run(Config::YSE_PAY, $yseConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    public function yee()
    {
        $callback = new $this();
        $yeeConfig = [
            'merCode'=>'',
            'merKey'=>'',
        ];
        try {
            $ret = Notify::run(Config::YEE_PAY, $yeeConfig, $callback);// 处理回调，内部进行了签名检查
            return $ret;
        } catch (PayException $e) {
            new PayException($e->errorMessage());
        }
    }

    /**
     * 实现接口的方法
     * 处理业务逻辑
     * @param array $data
     * @return bool|mixed
     */
    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        if ($channel === Config::IPS_PAY) {
            // 环讯支付
            $amount = $data['GateWayRsp']['body']['Amount'];
            $tripOrderNo = $data['GateWayRsp']['body']['IpsBillNo'];
            $merOrderNo = $data['GateWayRsp']['body']['MerBillNo'];
            echo '处理环讯支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } elseif ($channel === Config::SAND_PAY) {
            // 杉德支付
            $amount = $data['body']['totalAmount'];
            $tripOrderNo = $data['body']['tradeNo'];
            $merOrderNo = $data['body']['orderCode'];
            echo '处理杉德支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } elseif ($channel === Config::UNION_PAY) {
            // 银联支付
            $amount = $data['settleAmt'];
            $tripOrderNo = $data['queryId'];
            $merOrderNo = $data['orderId'];
            echo '处理银联支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } elseif ($channel === Config::YSE_PAY) {
            // 银盛支付
            $amount = $data['total_amount'];
            $tripOrderNo = $data['trade_no'];
            $merOrderNo = $data['out_trade_no'];
            echo '处理银盛支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } elseif ($channel === Config::YEE_PAY) {
            // 易宝支付
            $amount = $data['r3_Amt'];
            $tripOrderNo = $data['r8_MP'];
            $merOrderNo = $data['r6_Order'];
            echo '处理易宝支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } elseif ($channel === Config::UNS_PAY) {
            // 银生宝支付
            $amount = $data['amount'];
            $tripOrderNo = $data['unsTransId'];
            $merOrderNo = $data['orderId'];
            echo '处理银生宝支付异步通知，验签在内部完成，开发者只需要处理好订单业务逻辑。因补单机制，注意防重处理<br>';
        } else {
            // 其它类型的通知
            $amount = '';
            $tripOrderNo = '';
            $merOrderNo = '';
        }
        echo '订单金额'.$amount. '分 三方订单号'. $tripOrderNo . ' 商户订单号'.$merOrderNo.'<br>';
        // 执行业务逻辑，成功后返回true
        return true;
    }
}
