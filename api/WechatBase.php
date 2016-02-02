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
    const EVENT = 'event';
    const SUBSCRIBE = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';
    const SCAN = 'SCAN';
    const VIEW = 'VIEW';


    private $appId = "";
    private $appSecret = "";
    private $token = "";
    private $encodingAESKey = "";
    private $access_token = "";

    public $result = "";
    public $replay = array();

    private $api_url = 'https://api.weixin.qq.com';

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
    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("access_token.php"));
        if ($data->expire_time < time()) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file("access_token.php", json_encode($data));
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
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
        $this->replay['Image']['MediaId'] = $MediaId;
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

    public function receiveEvent()
    {
        //
    }

    public function receiveText()
    {
        //
    }

    public function receiveImage()
    {
        //
    }

    public function receiveVoice()
    {
        //
    }

    public function receiveVideo()
    {
        //
    }

    public function receiveShortVoice()
    {
        //
    }

    public function receiveLocation()
    {
        //
    }

    public function receiveLink()
    {
        //
    }

    public function sendNews($Articles, $fromUsername, $toUsername)
    {
        $ArticleCount = count($Articles);
        $textTpl = '<xml>
<ToUserName><![CDATA['.$fromUsername.']]></ToUserName>
<FromUserName><![CDATA['.$toUsername.']]></FromUserName>
<CreateTime>'.time().'</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>'.$ArticleCount.'</ArticleCount>
<Articles>';
foreach ($Articles as $Article) {
$textTpl .= '<item>
<Title><![CDATA['.$Article['Title'].']]></Title>
<Description><![CDATA['.$Article['Description'].']]></Description>
<PicUrl><![CDATA['.$Article['PicUrl'].']]></PicUrl>
<Url><![CDATA['.$Article['Url'].']]></Url>
</item>';
}
$textTpl .= "</Articles>
</xml>";
    echo $textTpl;
    exit;
    }
    public function createMenu($menu)
    {
        $access_token = $this->getAccessToken();
        $menu_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
        $this->httpPost($menu_url, $menu);
    }
    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
    private function httpPost($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    private function get_php_file($filename)
    {
        return trim(substr(file_get_contents($filename), 15));
    }
    private function set_php_file($filename, $content)
    {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
    public function log($str,$key='')
    {
        if(DEBUG) {
            $d = date("[Y-m-d H:i:s]");
            error_log($key.$d.$str."\n", 3, ERROR_LOG_FILE);
        }
    }
}
