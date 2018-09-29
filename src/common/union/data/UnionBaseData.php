<?php
namespace payment\common\union\data;

use payment\common\BaseData;
use payment\common\PayException;
/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 银联相关接口数据处理类
 * @link https://github.com/lettellyou/payment
 */

abstract class UnionBaseData extends BaseData
{

    /**
     * 获取签名
     * @param $signStr
     * @return mixed|string
     * @throws PayException
     */
    public function makeSign($signStr)
    {
        $info = [];
        switch ($this->signMethod) {
            case '01':
                $data = $this->initSignCert();
                $private_key = $data['key'];
                $signStr['certId'] = $data['certId'];
                $signStr = $this->buildSignData($signStr);
                $params_sha256x16 = hash( 'sha256',$signStr);
                $flag = openssl_sign ( $params_sha256x16, $signature, $private_key, 'sha256');
                $info['certId'] = $data['certId'];
                if ($flag) {
                    $signature_base64 = base64_encode ( $signature );
                    $info['signature'] = $signature_base64;
                } else {
                    throw new PayException('ERROR::openssl_sign');
                }
                break;
            default:
                $info = [];
        }
        return $info;
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
     * 初始化私钥
     * @return mixed
     * @throws PayException
     */
    protected function initSignCert()
    {
        $pkcs12Certdata = file_get_contents($this->privateKey);
        if($pkcs12Certdata === false ){
            throw new PayException('loadPk12Cert::file_get_contents');
        }
        if(openssl_pkcs12_read ( $pkcs12Certdata, $certs, $this->privateKeyPass ) == FALSE ){
            throw new PayException('loadPk12Cert::openssl_pkcs12_read');
        }
        $x509data = $certs ['cert'];
        if(!openssl_x509_read ( $x509data )){
            throw new PayException('loadPk12Cert::openssl_x509_read');
        }
        $certdata = openssl_x509_parse ( $x509data );
        $data['certId'] = $certdata ['serialNumber'];
        $data['key'] = $certs ['pkey'];
        return $data;
    }

    /**
     * 构建必须数据
     * @return mixed
     */
    abstract protected function getParams();
}