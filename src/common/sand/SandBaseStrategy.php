<?php
namespace payment\common\sand;

use payment\common\BaseStrategy;
use Payment\Common\PayException;
use payment\common\sand\SandConfig;
use Payment\Config;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德支付
 * @link https://github.com/lettellyou/payment
 */

abstract class SandBaseStrategy implements BaseStrategy
{

    /**
     * 环讯支付配置
     * @var
     */
    protected $config;
    /**
     * 环讯支付请求数据
     * @var
     */
    protected $reqData;

    public function __construct(array $config)
    {
        try {
            $this->config = new SandConfig($config);
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