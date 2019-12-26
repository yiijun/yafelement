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

    public $controller;

    public $action;

    public function init()
    {
        $this->controller = $this->_request->controller;
        $this->action = $this->_request->action;
        $this->model = $this->controller.'Model';
        $this->_view->assign([
            'controller' => $this->controller,
            'action'=> $this->action
        ]);
    }
}