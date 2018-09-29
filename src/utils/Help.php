<?php
namespace payment\utils;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 辅助功能
 * @link      https://github.com/lettellyou/payment
 */

class Help
{
    public static function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $ip, $onlineipmatches);
        $ip = empty($onlineipmatches[0]) ? 'unknown' : $onlineipmatches[0];
        return $ip;
    }
}