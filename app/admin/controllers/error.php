<?php
/**
 * User: tangyijun
 * Date: 2019-12-02
 * Time: 14:58
 */
class ErrorController extends Yaf\Controller_Abstract
{
    public function errorAction($exception) {
        $this->getView()->assign("exception", $exception);
    }
}