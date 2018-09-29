<?php
namespace payment\common\sand\data;

use payment\common\BaseData;
use payment\common\PayException;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 衫德相关接口数据处理类
 * @link https://github.com/lettellyou/payment
 */

abstract class SandBaseData extends BaseData
{
    /**
     * 获取签名
     * @param $signStr
     * @return mixed|string
     * @throws PayException
     */
    public function makeSign($signStr)
    {
        $signStr = $this->buildSignData($signStr);
        $signStr = json_encode($signStr);
        try {
            $resource = openssl_pkey_get_private($this->privateKey);
            $result = openssl_sign($signStr,$sign,$resource);
            openssl_free_key($resource);
            if(!$result){
                throw new PayException('签名出错'.$signStr);
            }
            return base64_encode($sign);
        } catch (PayException $e){
            throw $e;
        }
    }

    /**
     * 验签
     * @param $signStr
     * @param $sign
     * @param $publicKey
     * @return int
     * @throws PayException
     */
    protected function verify($signStr, $sign, $publicKey)
    {
        $resource = openssl_pkey_get_public($publicKey);
        $result = openssl_verify($signStr,base64_decode($sign),$resource);
        openssl_free_key($resource);
        if(!$result){
            throw new PayException('签名验证未通过,plainText:'.$signStr.'。sign:'.$sign,'02002');
        }
        return $result;
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
     * 获取公钥
     * @param $path  绝对路径
     * @return mixed
     * @throws PayException
     */
    protected function loadX509Cert($path)
    {
        try {
            $file = file_get_contents($path);
            if(!$file){
                throw new PayException('loadx509Cert::file_get_contents ERROR');
            }
            $cert = chunk_split(base64_encode($file),64,"\n");
            $cert = "-----BEGIN CERTIFICATE-----\n".$cert."-----END CERTIFICATE-----\n";
            $res = openssl_pkey_get_public($cert);
            $detail = openssl_pkey_get_details($res);
            openssl_free_key($res);
            if(!$detail){
                throw new PayException('loadX509Cert::openssl_pkey_get_details ERROR');
            }
            return $detail['key'];
        } catch(PayException $e){
            throw $e;
        }
    }

    /**
     * 获取私钥
     * @param $path 绝对地址路径
     * @param $pwd  私钥密码
     * @return mixed
     * @throws PayException
     */
    protected function loadPk12Cert($path, $pwd)
    {
        try {
            $file = file_get_contents($path);
            if(!$file){
                throw new PayException('loadPk12Cert::file_get_contents');
            }
            if(!openssl_pkcs12_read($file,$cert,$pwd)){
                throw new PayException('loadPk12Cert::openssl_pkcs12_read ERROR');
            }
            return $cert['pkey'];
        } catch(PayException $e){
            throw $e;
        }
    }

    /**
     * @param $url
     * @param $param
     * @return bool|mixed
     * @throws PayException
     */
    protected function curlReq($url, $param)
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $param = http_build_query($param);
        try {
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //正式环境时解开注释
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            if (!$data) {
                throw new PayException('请求出错');
            }
            return $data;
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 构建必须数据
     * @return mixed
     */
    abstract protected function getParams();
}