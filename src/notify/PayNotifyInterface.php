<?php
namespace payment\notify;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 提供给客户端实现的 支付异步回调 接口
 * @link https://github.com/lettellyou/payment
 * @property string $subject
 */
interface PayNotifyInterface
{

    /**
     * 处理商户业务逻辑
     * @param array $data
     * @return mixed
     */
    public function notifyProcess(array $data);
}
