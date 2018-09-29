<?php
/**
 * 第三方支付回调处理
 * @author: helei
 * @createTime: 2016-07-25 15:57
 * @description: 支付通知回调demo类
 */

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/NotifyClass.php';

$notify = new NotifyClass();
$action = (isset($_GET['action']) && $_GET['action'])?$_GET['action']:'ips';
$res = $notify->$action();
echo '<pre>';
print_r($res);
