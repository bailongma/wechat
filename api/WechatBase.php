<?php
namespace api;
defined('IN')?:exit('not access');

/**
*
*/
abstract class WechatBase
{
    const TEXT = 'text';
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const SHORTVIDEO = 'shortvideo';
    const LOCATION = 'location';
    const LINK = 'link';

    private $appId = "";
    private $appSecret = "";
    private $token = "";
    private $encodingAESKey = "";
    private $access_token = "";

    public $result = "";
    public $replay = array();

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
        $resultStr = '<xml>';
        foreach ($this->replay as $key => $value) {
            $resultStr .= '<'.$key.'>';
            if(is_array($value)) {
                foreach ($value as $k => $v) {
                    $resultStr .= '<'.$k.'>'.$v.'</'.$k.'>';
                }
            } else {
                if($key=='CreateTime') {
                    $resultStr .= $value;
                } else {
                    $resultStr .= '<![CDATA['.$value.']]>';
                }
            }
            $resultStr .= '</'.$key.'>';
        }
        if(!array_key_exists('CreateTime', $this->replay)) {
            $resultStr .= '<CreateTime>'.time().'</CreateTime>';
        }
        $resultStr .= '</xml>';
        $this->log($resultStr,'result');
        echo $resultStr;
        exit;
    }
    public function getContent()
    {
        return $this->result->Content;
    }
    public function setContent($Content)
    {
        $this->replay['Content'] = $Content;
        return $this;
    }
    public function getToUserName()
    {
        return $this->result->ToUserName;
    }
    public function setToUserName($ToUserName)
    {
        $this->replay['ToUserName'] = $ToUserName;
        return $this;
    }
    public function getFromUserName()
    {
        return $this->result->FromUserName;
    }
    public function setFromUserName($FromUserName)
    {
        $this->replay['FromUserName'] = $FromUserName;
        return $this;
    }
    public function getCreateTime()
    {
        return $this->result->CreateTime;
    }
    public function setCreateTime($CreateTime)
    {
        $this->replay['CreateTime'] = $CreateTime;
        return $this;
    }
    public function getMsgType()
    {
        return $this->result->MsgType;
    }
    public function setMsgType($MsgType)
    {
        $this->replay['MsgType'] = $MsgType;
        return $this;
    }
    public function getMediaId()
    {
        return $this->result->MediaId;
    }
    public function setMediaId($MediaId)
    {
        $this->replay['MediaId'] = $MediaId;
        return $this;
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
