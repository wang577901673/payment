<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯web快捷支付
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
    'merCode'=>'111111',
    'account'=>'1111110015',
    'merKey'=>'RAeYrKL06dmHQ041guBGb0UtOeOY3MKOpC4Nea4F5DkYWXmvPxzDbwUjS68kgry2mVp4SeLgrQyvcUVxSScQ0wS3LtkybPToY5UaphGnl78ndmja3P5kcAyvEeOlHZCU',
    'returnUrl'=>'https://www.baidu.com',
    'notifyUrl'=>'https://www.baidu.com',
];
$res = Charge::run(Config::IPS_CHANNEL_WEB_QUICK,$ipsConfig,$payData);
var_dump($res['html']);exit;
