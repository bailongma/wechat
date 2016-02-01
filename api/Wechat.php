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
                if($Content=='2') {
                    $Artcles[0]['Title']='aaa';
                    $Artcles[0]['Description']='aaaaaaa';
                    $Artcles[0]['PicUrl']='http://xinzhanguo.cn/img/aaaa.jpg';
                    $Artcles[0]['Url']='http://xinzhanguo.cn';
                    $Artcles[1]['Title']='bbbb';
                    $Artcles[1]['Description']='bbbbbbb';
                    $Artcles[1]['PicUrl']='http://xinzhanguo.cn/img/bbbb.jpg';
                    $Artcles[1]['Url']='http://xinzhanguo.cn';
                    $Artcles[2]['Title']='cccc';
                    $Artcles[2]['Description']='ccccccc';
                    $Artcles[2]['PicUrl']='http://xinzhanguo.cn/img/cccc.jpg';
                    $Artcles[2]['Url']='http://xinzhanguo.cn';
                    $this->sendNews($Artcles,  $result->FromUserName, $result->ToUserName);
                } else {
                    $this->sendText($result->FromUserName, $result->ToUserName, self::TEXT, $Content);
                }
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
