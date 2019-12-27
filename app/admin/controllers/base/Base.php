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