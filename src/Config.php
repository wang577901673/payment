<?php
namespace payment;

/**
 * @author: zero
 * @Class Config
 * @createTime: 2018-09-14
 * @description: 支付相关的基础配置  无法被继承
 * @link https://github.com/lettellyou/payment
 */

final class Config
{
    const VERSION = '1.0.0';

    /**********************支付通道相关常量*************************/
    const IPS_CHANNEL_WEB_GATEWAY = 'ips_web_gateway';// 环讯 PC 网关支付
    const IPS_CHANNEL_WEB_QUICK = 'ips_web_quick';// 环讯 PC 快捷支付
    const IPS_CHANNEL_WAP_QUICK = 'ips_wap_gateway';// 环讯 wap 快捷支付

    const SAND_CHANNEL_WEB_GATEWAY = 'sand_web_gateway';// 衫德 PC 网关支付
    const SAND_CHANNEL_WEB_QUICK = 'sand_web_quick';// 衫德 PC 快捷支付
    const SAND_CHANNEL_WAP_QUICK = 'sand_wap_gateway';// 衫德 wap 快捷支付

    const YSE_CHANNEL_WEB_GATEWAY = 'yse_web_gateway';// 银盛 PC 网关支付

    const UNION_CHANNEL_WEB_GATEWAY = 'union_web_gateway';// 银联 PC 网关支付
    const UNION_CHANNEL_WAP_GATEWAY = 'union_wap_gateway';// 银联 wap 网关支付

    const YEE_CHANNEL_WEB_GATEWAY = 'yee_web_gateway';// 易宝 PC 网关支付

    const UNS_CHANNEL_WEB_GATEWAY = 'uns_web_gateway';// 银生宝 PC 网关支付

    /**********************支付渠道相关常量*************************/
    const IPS_PAY = 'ips_pay';          //环讯支付
    const SAND_PAY = 'sand_pay';        //杉德支付
    const YSE_PAY = 'yse_pay';          //银盛支付
    const UNION_PAY = 'union_pay';      //银联支付
    const YEE_PAY = 'yee_pay';          //易宝支付
    const UNS_PAY = 'uns_pay';          //银生宝支付

    /**********************返回状态码常量*************************/
    const TRADE_SUCCESS_CODE = 200;      //交易成功
    const TRADE_FAIL_CODE = 500;      //交易失败
    const TRADE_DELL_CODE = 100;      //交易处理中

    /**********************返回信息常量*************************/
    const TRADE_SUCCESS = '交易成功';      //交易成功
    const TRADE_FAIL = '交易失败';      //交易失败
    const TRADE_DELL = '交易处理中';      //交易处理中

    /**********************返回数据格式常量*************************/
    const IS_FORM = 1;      //表单数据
    const IS_URL = 2;      //URL链接跳转
    const IS_QRCODE = 3;      //二维码数据
    const IS_DATA = 4;      //同步响应数据

    /**********************金额常量*************************/
    const PAY_MIN_FEE = 1;// 支付的最小金额，单位分
}