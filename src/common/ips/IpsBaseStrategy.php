<?php
namespace payment\common\ips;

use payment\common\BaseStrategy;
use Payment\Common\PayException;
use payment\common\ips\IpsConfig;
use Payment\Config;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯支付
 * @link https://github.com/lettellyou/payment
 */

abstract class IpsBaseStrategy implements BaseStrategy
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
            $this->config = new IpsConfig($config);
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