<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯web网关支付demo
 * @link https://github.com/lettellyou/payment
 */

require_once __DIR__ . '/../../autoload.php';
use payment\common\PayException;
use payment\client\Charge;
use payment\Config;

$payData = [
    'orderNo' => date('YmdHis'),
    'amount' => 10,
    //'attach' => '用户充值',
    //'title' => '用户充值',
];
$ipsConfig = [
    'merCode'=>'',
    'account'=>'',
    'merKey'=>'',
    'returnUrl'=>'https://www.baidu.com',
    'notifyUrl'=>'https://www.baidu.com',
];
$res = Charge::run(Config::IPS_CHANNEL_WEB_GATEWAY,$ipsConfig,$payData);
var_dump($res['html']);exit;