<?php
namespace payment\common\union;

use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  银联配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class UnionConfig extends ConfigInterface
{

    /**
     * 版本号
     * @var string
     */
    public $version = '5.1.0';

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 交易类型
     * @var
     */
    public $txnType = '01';

    /**
     * 交易子类
     * @var string
     */
    public $txnSubType = '01';

    /**
     * 业务类型
     * @var
     */
    public $bizType;

    /**
     * 签名方法
     * 01 RSA
     * @var string
     */
    public $signMethod = '01';

    /**
     * 渠道类型
     * 07-PC，08-手机
     * @var
     */
    public $channelType;

    /**
     * 接入类型
     * @var string
     */
    public $accessType = '0';

    /**
     * 交易币种
     * @var string
     */
    public $currencyCode = '156';

    public $company = '中国银联股份有限公司';

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
     * 根证书
     * @var
     */
    public $rootCertPath;

    /**
     * 业务代码
     * @var string
     */
    public $businessCode = '3010002';

    /**
     * 编码格式
     * @var string
     */
    public $encoding = 'UTF-8';


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

        if (key_exists('rootCertPath', $config) && $config['rootCertPath']) {
            $this->rootCertPath = $config['rootCertPath'];
        } else {
            throw new PayException('请配置跟证书，配置名：rootCertPath');
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