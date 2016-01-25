<?php
namespace api;
defined('IN')?:exit('not access');

/**
*
*/
abstract class WechatBase
{
    const api_version = "1";
    const TEXT = 'text';
    function __construct($argument=[])
    {
        # code...
    }
    public function valid()
    {
        if($this->checkSignature()) {
            echo $_GET["echostr"];
            $this->log('checktoken', 'token');
            exit;
        }
    }
    private function checkSignature()
    {
        if($_GET){
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $token = TOKEN;
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode( $tmpArr );
            $tmpStr = sha1( $tmpStr );

            if( $tmpStr == $signature ){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    public function parseData(){
        $return = array();
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            return $postObj;
        }else {
            return $return;
        }
    }

    public function sendText($fromUsername,$toUsername,$msgType,$content)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $content);
        echo $resultStr;
    }

    public function log($str,$key='')
    {
        $d = date("[Y-m-d H:i:s]");
        error_log($key.$d.$str."\n", 3, ERROR_LOG_FILE);
    }
}
