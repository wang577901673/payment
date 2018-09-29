<?php
namespace payment\common\sand;
use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  衫德配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class SandConfig extends ConfigInterface
{

    /**
     * 版本号
     * @var string
     */
    public $version = '1.0';

    /**
     * 接口名称
     * @var
     */
    public $method;

    /**
     * 支付模式
     * bank_pc-银行网关支付
     * sand_h5-杉德H5银行卡支付
     * @var string
     */
    public $payMode = 'bank_pc';

    /**
     * 产品编码
     * @var string
     */
    public $productId = '00000007';

    /**
     * 接入类型 1-普通商户接入
     * @var string
     */
    public $accessType = '1';

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 渠道类型 07-互联网 08-移动端
     * @var string
     */
    public $channelType = '07';

    /**
     * 支付方式 1-网银支付
     * @var string
     */
    public $payType = '1';

    /**
     * 商户公钥
     * @var
     */
    public $publicKey;

    /**
     * 商户私钥
     * @var
     */
    public $privateKey;

    /**
     * 私钥证书密码
     * @var
     */
    public $privateKeyPass;

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

        if (key_exists('publicKey', $config) && $config['publicKey']) {
            $this->publicKey = $config['publicKey'];
        } else {
            throw new PayException('请配置商户公钥，配置名：publicKey');
        }

        if (key_exists('privateKey', $config) && $config['privateKey']) {
            $this->privateKey = $config['privateKey'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置商户私钥，配置名：privateKey');
            }
        }

        if (key_exists('privateKeyPass', $config) && $config['privateKeyPass']) {
            $this->privateKeyPass = $config['privateKeyPass'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置商户私钥密码，配置名：privateKeyPass');
            }
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

        if(key_exists('productId', $config) && $config['productId']) {
            $this->productId = $config['productId'];
        }

        if(key_exists('channelType', $config) && $config['channelType']) {
            $this->channelType = $config['channelType'];
        }

        if(key_exists('returnRaw', $config) && $config['returnRaw']) {
            $this->returnRaw = $config['returnRaw'];
        }
    }
}