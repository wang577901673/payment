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
    'merCode'=>'208547',
    'account'=>'2085470017',
    'merKey'=>'CFFyQ69dquv6YOnagFHDRUoPNfjWQpRmVlvLzxfz8cSyafR8nRsjE4Qtgs2w551gfRoZjT4Pdrl0b95PmZ4nr4o1njYsxUVrA3VpLnBafRKkokZZh8Kg3VgbeKUPR7jM',
    'returnUrl'=>'https://www.baidu.com',
    'notifyUrl'=>'https://www.baidu.com',
];
$res = Charge::run(Config::IPS_CHANNEL_WEB_QUICK,$ipsConfig,$payData);
var_dump($res['html']);exit;
