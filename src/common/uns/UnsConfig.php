<?php
namespace payment\common\uns;

use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  银生宝配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class UnsConfig extends ConfigInterface
{

    /**
     * 版本号
     * @var string
     */
    public $version = '2.0.0';

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 商户秘钥
     * @var
     */
    public $merKey;

    /**
     * 响应方式
     * 1-页面响应， 2-后台响应， 3-两者都需
     * @var string
     */
    public $responseMode = '3';

    /**
     * 货币类型
     * @var string
     */
    public $currencyType = 'CNY';

    /**
     * 是否通过银生担保支付
     * false-非担保交易   true-担保交易
     * @var bool
     */
    public $assuredPay = false;

    /**
     * 是否 B2B 网上银行支付
     * @var bool
     */
    public $b2b = false;

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