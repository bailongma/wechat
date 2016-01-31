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
            if ($result->MsgType==self::IMAGE) {
                $this->setToUserName($result->FromUserName)
                ->setFromUserName($result->ToUserName)
                ->setMsgType(self::IMAGE)
                ->setMediaId($result->MediaId)
                ->replay();
            } elseif($result->MsgType==self::TEXT) {
                $Content = $this->keyword($result->Content);
                $this->sendText($result->FromUserName, $result->ToUserName, self::TEXT, $Content);
            } else {
                $this->sendText($result->FromUserName, $result->ToUserName, self::TEXT, '');
            }
        }
    }
    public function keyword($word)
    {
        $word = trim($word);
        if($word=='1') {
            return 'hahahahahaha';
        } else {
            return $word;
        }
    }
}
