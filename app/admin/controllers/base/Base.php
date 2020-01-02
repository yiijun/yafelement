<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 16:56
 */
namespace Base;
use \Libs\Comm\Common;
use \Libs\File\Upload;
class BaseController extends \Yaf\Controller_Abstract
{
    public $model;

    public $controller;

    public $action;

    public $login_info = null;

    public $config = [];

    public function init()
    {
        $this->login_info = \Libs\Cache\Session::getInstance()->get('login_info');
        if(empty($this->login_info)) {
            $this->redirect("/Login/index");
        }
        $this->controller = $this->_request->controller;
        $this->action = $this->_request->action;
        $this->model = $this->controller.'Model';
        $this->_view->assign([
            'controller' => $this->controller,
            'action'=> $this->action
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