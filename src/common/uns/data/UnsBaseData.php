<?php
namespace payment\common\uns\data;

use payment\common\BaseData;
use payment\common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银生宝相关接口数据处理类
 * @link https://github.com/lettellyou/payment
 */

abstract class UnsBaseData extends BaseData
{

    /**
     * 获取签名
     * @param $signStr
     * @return mixed|string
     */
    public function makeSign($signStr)
    {
        $signStr = $this->buildSignData($signStr);
        return strtoupper(md5($signStr));
    }

    /**
     * 构建表单数据
     * @param $url
     * @param $data
     * @param string $method
     * @return string
     */
    protected function buildForm($url, $data, $method = 'POST')
    {
        $sHtml = "<!DOCTYPE html><html><head><title>Waiting...</title>";
        $sHtml .= "<meta http-equiv='content-type' content='text/html;charset=utf-8'></head>";
	    $sHtml .= "<body><form id='submit' name='submit' action='" . $url . "' method='" . $method . "'>";
        foreach ($data as $key => $value) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $value . "' style='width:90%;'/>";
        }
        $sHtml .= "</form>正在提交信息...";
        $sHtml .= "<script>document.forms['submit'].submit();</script></body></html>";
        return $sHtml;
    }

    /**
     * @param $data
     * @return string
     * @throws PayException
     */
    protected function signEncrypt($data)
    {
        $pkcs12 = file_get_contents($this->privateKey);
        if(!$pkcs12) {
            throw new PayException('loadPk12Cert::file_get_contents');
        }
        if (openssl_pkcs12_read($pkcs12, $certs, $this->privateKeyPass)) {
            $privateKey = $certs['pkey'];
            $publicKey = $certs['cert'];
            $signedMsg = "";
            if (openssl_sign($data, $signedMsg, $privateKey, OPENSSL_ALGO_SHA1)) {
                return base64_encode($signedMsg);
            } else {
                throw new PayException('loadPk12Cert::openssl_sign');
            }
        } else {
            throw new PayException('loadPk12Cert::openssl_pkcs12_read');
        }
    }

    /**
     * 构建必须数据
     * @return mixed
     */
    abstract protected function getParams();
}