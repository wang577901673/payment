<?php
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联web网关支付demo
 * @link https://github.com/lettellyou/payment
 */

require_once __DIR__ . '/../../autoload.php';
use payment\common\PayException;
use payment\client\Charge;
use payment\Config;
$payData = [
    'orderNo' => date('YmdHis'),
    'amount' => 100,    //单位 分
    //'clientIp' => '127.0.0.1',
    //'attach' => '用户充值',
    //'title' => '用户充值',
];
$ipsConfig = [
    'merCode'=>'',              //商户号
    'publicKey'=>__DIR__. '/cert/public.cer',//公钥，绝对物理路径
    'privateKey'=>__DIR__. '/cert/private.pfx',//私钥，绝对物理路径
    'rootCertPath'=>__DIR__. '/cert/rootCert.cer',//跟证书，绝对物理路径
    'privateKeyPass'=>'',//私钥密码
    'returnUrl'=>'https://www.baidu.com',//同步返回地址
    'notifyUrl'=>'https://www.baidu.com',//异步通知地址
];
$res = Charge::run(Config::UNION_CHANNEL_WEB_GATEWAY,$ipsConfig,$payData);
echo $res['html'];