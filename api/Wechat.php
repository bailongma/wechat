<?php
namespace api;
defined('IN')?:exit('not access');

use api\WechatBase as WechatBase;
/**
*
*/
class Wechat extends WechatBase
{
    public function __construct($argument=[])
    {
        parent::__construct($argument);
        $this->valid();
        $result = $this->parseData();
        if($result) {
            $this->sendText($result->FromUserName, $result->ToUsername, self::TEXT, $result->Content);
        }
    }
}
