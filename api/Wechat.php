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
                    $Artcles[0]['Title']='aaaa';
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
                    $this->sendText($result->FromUserName, $result->ToUserName, $Content);
                }
            } elseif($result->MsgType==self::VOICE) {
                //$this->sendVoice($result->FromUserName,$result->ToUserName,$result->MediaId);
                $this->sendText($result->FromUserName,$result->ToUserName,$result->Recognition);
            } elseif($result->MsgType==self::SHORTVIDEO) {
                $this->sendText($result->FromUserName, $result->ToUserName, '');
                //$this->sendVideo($result->FromUserName,$result->ToUserName,$result->MediaId,'shortvideo title','shortvideo desc');
            } elseif($result->MsgType==self::EVENT) {
                if($result->Event==self::SUBSCRIBE) {
                    $this->sendText($result->FromUserName, $result->ToUserName, 'welcome');
                } elseif($result->Event==self::SCAN) {
                    $this->sendText($result->FromUserName, $result->ToUserName, 'welcome');
                } else {
                    echo ' ';
                }
            } else {
                echo ' ';
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
    public function menu(){
        $menu = '{
            "button":[
                {
                    "name":"推荐",
                    "sub_button":[
                        {
                           "type":"click",
                           "name":"今日图片",
                           "key":"图片"
                        },
                        {
                           "type":"click",
                           "name":"今日音乐",
                           "key":"音乐"
                        },
                        {
                            "type":"click",
                            "name":"今日视频",
                            "key":"视频"
                        },
                        {
                            "type":"click",
                            "name":"今日文字",
                            "key":"文字"
                        }
                    ]
                },
                {
                    "name":"逛逛",
                    "sub_button":[
                        {
                            "type":"click",
                            "name":"点击推事件",
                            "key":"gclick"
                        },
                        {
                           "type":"pic_weixin",
                           "name":"弹出微信相册发图器",
                           "key":"gpic_weixin"
                        },
                        {
                            "type":"scancode_push",
                            "name":"扫码推事件",
                            "key":"gscancode"
                        },
                        {
                            "type":"location_select",
                            "name":"弹出地理位置选择器",
                            "key":"glocation"

                        },
                        {
                            "type":"view",
                            "name":"网页",
                            "url":"http://xinzhanguo.cn"

                        }
                    ]
                },
                {
                   "type":"click",
                   "name":"简介",
                   "key":"简介"
                }
            ]
        }';
        $res = $this->createMenu($menu);
        var_dump($res);
    }
}
