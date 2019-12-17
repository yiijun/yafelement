<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 16:56
 */
namespace Base;
class BaseController extends \Yaf\Controller_Abstract
{
    public $model;

    public function init()
    {
        $this->model = $this->getRequest()->controller.'Model';
    }
}