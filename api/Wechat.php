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
            $this->log(json_encode($result), 'post');
            if($result->MsgType==self::TEXT) {
                $this->sendText($result->fromUsername, $result->toUsername, self::TEXT, $result->Content);
            }
        }
    }
}
