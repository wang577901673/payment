<?php
namespace payment\common;

use payment\common\ConfigInterface;
use payment\common\ips\IpsConfig;
use payment\common\sand\SandConfig;
use payment\common\union\UnionConfig;
use payment\common\yee\YeeConfig;
use payment\common\yse\YseConfig;

use payment\Config;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 支付相关接口的数据基类
 * @link      https://github.com/lettellyou/payment
 */

abstract class BaseData
{
    /**
     * 支付的请求数据
     * @var
     */
    protected $data;

    /**
     * 支付返回的数据
     * @var
     */
    protected $retData;

    /**
     * 配置类型
     * @var
     */
    protected $channel;

    public function __construct(ConfigInterface $config, array $reqData)
    {
        if ($config instanceof IpsConfig) {
            $this->channel = Config::IPS_PAY;
        } elseif ($config instanceof SandConfig) {
            $this->channel = Config::SAND_PAY;
        } elseif ($config instanceof UnionConfig) {
            $this->channel = Config::UNION_PAY;
        } elseif ($config instanceof YeeConfig) {
            $this->channel = Config::YEE_PAY;
        } elseif ($config instanceof YseConfig) {
            $this->channel = Config::YSE_PAY;
        }
        $this->data = array_merge($config->toArray(), $reqData);
        try {
            $this->checkReqData();
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 获取变量，通过魔术方法
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * 设置变量
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * 签名算法实现
     * @param $signStr
     * @return mixed
     */
    abstract protected function makeSign($signStr);

    /**
     * 构建用于支付的签名相关数据
     * @param $data
     * @return mixed
     */
    abstract protected function buildSignData($data);

    /**
     * 检查传入的参数. $reqData是否正确
     * @return mixed
     */
    abstract protected function checkReqData();
}