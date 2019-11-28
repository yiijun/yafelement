<?php
/**
 * User: tangyijun
 * Date: 2019-11-26
 * Time: 15:48
 */
define('ENVIRON',strtolower(ini_get('yaf.environ')));
require __DIR__.'/../../vendor/autoload.php';
$app = new \Yaf\Application("../../conf/".ENVIRON."/config.ini");