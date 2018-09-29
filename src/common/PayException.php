<?php
namespace payment\common;
use Throwable;

/**
 * @author: zero
 * @createTime: 2018-09-14
 * @description: 统一的异常处理类
 * @link      https://github.com/lettellyou/payment
 */
class PayException extends \Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        return $this->errorMessage();
    }

    /**
     * 获取异常错误信息
     * @return string
     * @author helei
     */
    public function errorMessage()
    {
        var_dump($this->message);exit;
        //return $this->getMessage();
    }
}
