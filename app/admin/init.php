<?php
/**
 * User: tangyijun
 * Date: 2019-11-26
 * Time: 15:48
 */
define('APP_NAME','admin');
define('APP_PATH', dirname(__FILE__));
define('SITE_NAME',$_SERVER['SERVER_NAME']);
$app = new \Yaf\Application(__DIR__."/../../conf/".YAF\ENVIRON."/".APP_NAME.".ini");