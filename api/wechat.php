<?php
namespace api;
defined('IN')?:exit('not access');


/**
*
*/
class Wechat
{
    private $appid = '';
    private $token = '';
    private $access_token = '';

    public function __construct($argument=[])
    {
        # code...
        echo  'hello wechat';
    }
}
