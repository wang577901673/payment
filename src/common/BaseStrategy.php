<?php
namespace payment\common;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 定义接口支付策略，每种策略都是具体实现handel和getBuildDataClass
 * @link      https://github.com/lettellyou/payment
 */
interface BaseStrategy
{
    /**
     * 处理具体的业务
     * @param array $data
     * @return mixed
     * @author helei
     */
    public function handle(array $data);

    /**
     * 获取支付对应的数据完成类
     * @return BaseData
     * @author helei
     */
    public function getBuildDataClass();
}
