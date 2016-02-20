<?php

define('IN', true);

require("common.php");

ob_start();
use api\Wechat;
$wechat = new Wechat($wx);
$page = ob_get_contents();
error_log($page, 3, ERROR_LOG_FILE);
ob_end_flush();
