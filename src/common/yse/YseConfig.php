<?php
namespace payment\common\yse;

use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  银盛配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class YseConfig extends ConfigInterface
{

    /**
     * 版本号
     * @var string
     */
    public $version = '3.0';

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 收款方银盛支付用户号
     * @var
     */
    public $sellerId;

    /**
     * 收款方银盛支付客户名
     * @var
     */
    public $sellerName;

    /**
     * 订单超时时间
     * @var
     */
    public $timeoutExpress;

    /**
     * 加密方式
     * @var string
     */
    public $signType = 'RSA';

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
     * 私钥密码
     * @var
     */
    public $privateKeyPass;

    /**
     * 业务代码
     * @var string
     */
    public $businessCode = '3010002';

    /**
     * 编码格式
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * 接口名称
     * @var
     */
    public $method;

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

        if (key_exists('sellerId', $config) && $config['sellerId']) {
            $this->sellerId = $config['sellerId'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置收款方银盛支付用户号，配置名：sellerId');
            }
        }

        if (key_exists('sellerName', $config) && $config['sellerName']) {
            $this->sellerName = $config['sellerName'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置收款方银盛支付客户名，配置名：sellerName');
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

        if(key_exists('returnRaw', $config) && $config['returnRaw']) {
            $this->returnRaw = $config['returnRaw'];
        }
    }
}