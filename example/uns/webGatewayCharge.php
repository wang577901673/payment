<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银生宝web网关支付demo
 * @link https://github.com/lettellyou/payment
 */

require_once __DIR__ . '/../../autoload.php';
use payment\common\PayException;
use payment\client\Charge;
use payment\Config;
$payData = [
    'orderNo' => date('YmdHis'),
    'amount' => 1,    //单位 分
    'bankCode' => 'CCB',    //单位 分
];
$ipsConfig = [
    'merCode'=>'',       //商户号
    'merKey'=>'',               //秘钥
    'returnUrl'=>'https://www.baidu.com',   //同步返回地址
    'notifyUrl'=>'http://payment.wyyang.com/example/logs/log.php',   //异步通知地址
];
$res = Charge::run(Config::UNS_CHANNEL_WEB_GATEWAY,$ipsConfig,$payData);
echo $res['html'];