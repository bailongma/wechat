<?php
namespace api;
defined('IN')?:exit('not access');

/**
*
*/
abstract class WechatBase
{
    const TEXT = 'text';
    private $appId = "";
    private $appSecret = "";
    private $token = "";
    private $encodingAESKey = "";
    private $access_token = "";

    public  $result =  "";
    private $token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
    function __construct($argument=[])
    {
        $this->appId = $argument['appId'];
        $this->appSecret = $argument['appSecret'];
        $this->token = $argument['token'];
        $this->encodingAESKey = $argument['encodingAESKey'];
    }
    public function valid()
    {
        if($this->checkSignature()) {
            echo $_GET["echostr"];
            $this->log('checktoken', 'token');
            exit;
        }
    }
    public function getAccessToken()
    {
        #&appid=APPID&secret=APPSECRET
    }
    private function checkSignature()
    {
        if(!empty($_GET["echostr"])){
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $tmpArr = array($this->token, $timestamp, $nonce);
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

    public function parseData()
    {
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)){
            $this->log($postStr,'post');
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->result = $postObj;
            return $postObj;
        }else {
            return false;
        }
    }

    public function replay()
    {
        #
    }
    public function getContent()
    {
        return $this->result->Content;
    }
    public function getToUserName()
    {
        return $this->result->ToUserName;
    }
    public function getFromUserName()
    {
        return $this->result->FromUserName;
    }
    public function getCreateTime()
    {
        return $this->result->CreateTime;
    }
    public function getMsgType()
    {
        return $this->result->CreateTime;
    }
    public function getMediaId()
    {
        return $this->result->MediaId;
    }
    public function getFormat()
    {
        return $this->result->Format;
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
        $this->log($resultStr,'result');
        echo $resultStr;
        exit;
    }

    public function log($str,$key='')
    {
        if(DEBUG) {
            $d = date("[Y-m-d H:i:s]");
            error_log($key.$d.$str."\n", 3, ERROR_LOG_FILE);
        }
    }
}
