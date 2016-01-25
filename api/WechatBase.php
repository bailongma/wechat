<?php
namespace api;
defined('IN')?:exit('not access');
define('ERROR_LOG_FILE', './logs/logs');
/**
*
*/
abstract class WechatBase
{
    const api_version = "1";

    function __construct()
    {
        # code...
    }

    public function errcode()
    {
        if($this->result) {
            $msg = json_decode($this->result);
            if($msg->errcode!='0') {
                error_log($this->result, 3, ERROR_LOG_FILE);
            }

        }
    }
}
