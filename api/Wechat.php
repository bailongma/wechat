<?php
namespace api;
defined('IN')?:exit('not access');

use api\WechatBase as WechatBase;
/**
*
*/
class Wechat extends WechatBase
{
    private $appid = '';
    private $token = '';
    private $access_token = '';

    public function __construct($argument=[])
    {
        $this->valid();
        $result = $this->parseData();
        if($result->MsgType==self::TEXT) {
            $this->sendText($result->fromUsername, $result->toUsername, self::TEXT, $result->Content);
        }

    }
}
