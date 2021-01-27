<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 16:56
 */
namespace base;
use libs\comm\common;
use libs\file\upload;
use libs\cache\session;
class BaseController extends \Yaf\Controller_Abstract
{
    public $model;

    public $controller;

    public $action;

    public $login_info = null;

    public $config = [];

    public function init()
    {
        $this->login_info = Session::getInstance()->get('login_info');
        if(empty($this->login_info)) {
            $this->redirect("/Login/index");
        }
        $this->controller = $this->_request->controller;
        $this->action = $this->_request->action;
        $this->model = $this->controller.'Model';
        $this->config = \ConfigModel::getInstance()->conf();
        $this->_view->assign([
            'controller' => $this->controller,
            'action'=> $this->action,
            'login_info' => $this->login_info,
            'config' => $this->config
        ]);
    }

    /**
     * 文件上传
     */
    public function uploadAction()
    {
        if($this->_request->isPost()){
            $get = $this->_request->getParams();
            if(empty($get['field']) || empty($get['name'])) {
                return Common::getInstance()->error('上传参数错误');
            }
            $path = Upload::getInstance()->post('upload/',['ext' => ['jpg','png'],'size' => 2000000]);
            return Common::getInstance()->success([
                'path' => "vm.{$get['name']}.{$get['field']} = '{$path}'"
            ]);
        }
        return Common::getInstance()->error(
            Upload::getInstance()->error
        );
    }
}