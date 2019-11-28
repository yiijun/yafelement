<?php
/**
 * User: tangyijun
 * Date: 2019-11-28
 * Time: 11:49
 */
class IndexController extends Yaf\Controller_Abstract
{
    public function indexAction()
    {
        $config = \Yaf\Application::app()->getConfig();
        var_dump($config);
       echo 1;exit;
    }
}