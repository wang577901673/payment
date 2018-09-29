<?php
namespace payment\common\ips\data;
use payment\common\BaseData;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 环讯相关接口数据处理类
 * @link https://github.com/lettellyou/payment
 */

abstract class IpsBaseData extends BaseData
{
    /**
     * 获取签名
     * @param $signStr
     * @return mixed|string
     */
    public function makeSign($signStr)
    {
        switch ($this->orderEncodeType) {
            case '5':
                $sign = md5($signStr);
                break;
            default:
                $sign = '';
        }
        return $sign;
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
     * 构建必须数据
     * @return mixed
     */
    abstract protected function getParams();
}