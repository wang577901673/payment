<?php
namespace payment\common\union;

use payment\common\BaseStrategy;
use Payment\Common\PayException;
use payment\common\union\UnionConfig;
use Payment\Config;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联支付
 * @link https://github.com/lettellyou/payment
 */

abstract class UnionBaseStrategy implements BaseStrategy
{

    /**
     * 银盛支付配置
     * @var
     */
    protected $config;
    /**
     * 银盛支付请求数据
     * @var
     */
    protected $reqData;

    public function __construct(array $config)
    {
        try {
            $this->config = new UnionConfig($config);
        } catch (PayException $e) {
            throw $e;
        }
    }

    public function handle(array $data)
    {
        $buildClass = $this->getBuildDataClass();
        try {
            $this->reqData = new $buildClass($this->config, $data);
        } catch (PayException $e) {
            throw $e;
        }
        return $this->reqData->buildData();
    }
}