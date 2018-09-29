<?php
namespace payment\common\ips;
use payment\common\ConfigInterface;
use payment\common\PayException;
use payment\utils\ArrayUtil;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description:  环讯配置文件  所有支付的配置文件，均需要继承 ConfigInterface 这个接口
 * @link      https://github.com/lettellyou/payment
 */

final class IpsConfig extends ConfigInterface
{

    /**
     * 版本号
     * @var string
     */
    public $version = 'v1.0.0';

    /**
     * 商户号
     * @var
     */
    public $merCode;

    /**
     * 交易账号
     * @var
     */
    public $account;

    /**
     * 商户秘钥
     * @var
     */
    public $merKey;

    /**
     * 货币 156 人民币
     * @var
     */
    public $ccy = '156';

    /**
     * 语言 GB 中文
     * @var string
     */
    public $lang = 'GB';

    /**
     * 支付方式 01#借记卡
     * @var string
     */
    public $payType = '01';

    /**
     * 加密方式，5采用md5
     * @var string
     */
    public $orderEncodeType = '5';

    /**
     * 返回方式 1 notifyUrl
     * @var string
     */
    public $retType = '1';

    /**
     * 交易返回接口加密方式  17 md5摘要认证
     * @var string
     */
    public $retEncodeType = '17';

    /**
     * 是否直连  空值表示非直连
     * @var string
     */
    public $isCredit = '';

    /**
     * 产品类型,1。个人网银，2.企业网银，直连必填
     * @var string
     */
    public $productType = '1';

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
        if (key_exists('merCode', $config)) {
            $this->merCode = $config['merCode'];
        } else {
            throw new PayException('请配置商户号，配置名：merCode');
        }

        if (key_exists('account', $config)) {
            $this->account = $config['account'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置交易账号，配置名：account');
            }
        }

        if (key_exists('merKey', $config)) {
            $this->merKey = $config['merKey'];
        } else {
            throw new PayException('请配置商户秘钥，配置名：merKey');
        }

        if (key_exists('returnUrl', $config)) {
            $this->returnUrl = $config['returnUrl'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置同步返回地址，配置名：returnUrl');
            }
        }

        if (key_exists('notifyUrl', $config)) {
            $this->notifyUrl = $config['notifyUrl'];
        } else {
            if($this->isCheck) {
                throw new PayException('请配置异步返回地址，配置名：notifyUrl');
            }
        }

        if(key_exists('productType', $config)) {
            $this->productType = $config['productType'];
        }

        if(key_exists('returnRaw', $config)) {
            $this->returnRaw = $config['returnRaw'];
        }
    }
}