<?php
namespace payment\common\yee;

use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  易宝配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class YeeConfig extends ConfigInterface
{

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 秘钥
     * @var
     */
    public $merKey;

    /**
     * 业务类型
     * @var string
     */
    public $cmd = 'Buy';

    /**
     * 交易币种
     * @var string
     */
    public $cur = 'CNY';

    /**
     * 商品种类
     * @var string
     */
    public $productCat = 'Finance';

    /**
     * 送货地址为空
     * @var int
     */
    public $address = 0;

    /**
     * 应答机制
     * @var string
     */
    public $prNeedResponse = '1';

    /**
     * 接口地址
     * @var
     */
    public $postUrl;

    /**
     * 同步返回地址
     * @var
     */
    public $returnUrl;

    /**
     * 异步返回地址
     * @var
     */
    public $notifyUrl;

    /**
     * 在处理回调时，是否直接返回原始数据
     * @var bool
     */
    public $returnRaw = false;

    public function initConfig(array $config)
    {
        $config = ArrayUtil::paraFilter($config);// 过滤掉空值，下面不用在检查是否为空
        if (key_exists('merCode', $config) && $config['merCode']) {
            $this->merCode = $config['merCode'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置商户号，配置名：merCode');
            }
        }

        if (key_exists('merKey', $config) && $config['merKey']) {
            $this->merKey = $config['merKey'];
        } else {
            throw new PayException('请配置秘钥，配置名：merKey');
        }

        if (key_exists('returnUrl', $config) && $config['returnUrl']) {
            $this->returnUrl = $config['returnUrl'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置同步返回地址，配置名：returnUrl');
            }
        }

        if (key_exists('notifyUrl', $config) && $config['notifyUrl']) {
            $this->notifyUrl = $config['notifyUrl'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置异步返回地址，配置名：notifyUrl');
            }
        }

        if(key_exists('returnRaw', $config) && $config['returnRaw']) {
            $this->returnRaw = $config['returnRaw'];
        }
    }
}