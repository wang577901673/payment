<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 易宝web网关支付demo
 * @link https://github.com/lettellyou/payment
 */

require_once __DIR__ . '/../../autoload.php';
use payment\common\PayException;
use payment\client\Charge;
use payment\Config;
$payData = [
    'orderNo' => date('YmdHis'),
    'amount' => 100,    //单位 分
    'bankCode' => '',    //单位 分
    //'clientIp' => '127.0.0.1',
    //'attach' => '用户充值',
    //'title' => '用户充值',
];
$ipsConfig = [
    'merCode'=>'',              //商户号
    'merKey'=>'4eiZ7e856994p43H456FEy6He0rcwy529257eT6z48n85FmmVTk1J94X84mt',              //秘钥
    'returnUrl'=>'https://www.baidu.com',//同步返回地址
    'notifyUrl'=>'https://www.baidu.com',//异步通知地址
];
$res = Charge::run(Config::YEE_CHANNEL_WEB_GATEWAY,$ipsConfig,$payData);
echo $res['html'];