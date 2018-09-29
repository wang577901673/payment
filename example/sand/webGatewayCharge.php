<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 杉德web网关支付
 * @link https://github.com/lettellyou/payment
 */

require_once __DIR__ . '/../../autoload.php';
use payment\common\PayException;
use payment\client\Charge;
use payment\Config;
$payData = [
    'orderNo' => date('YmdHis'),
    'amount' => 100,    //单位 分
    'bankCode' => '01050000',    //单位 分
    //'clientIp' => '127.0.0.1',
    //'attach' => '用户充值',
    //'title' => '用户充值',
];
$ipsConfig = [
    'merCode'=>'',
    'publicKey'=>__DIR__. '/cert/public.cer',
    'privateKey'=>__DIR__. '/cert/private.pfx',
    'privateKeyPass'=>'',
    'returnUrl'=>'https://www.baidu.com',
    'notifyUrl'=>'https://www.baidu.com',
];
$res = Charge::run(Config::SAND_CHANNEL_WEB_GATEWAY,$ipsConfig,$payData);
var_dump($res['html']);exit;