<?php
/**
 * User: tangyijun
 * Date: 2019-12-06
 * Time: 15:38
 */

use \Libs\Comm\Common;
abstract class ViewController extends \Base\BaseController
{

    public function init()
    {
        parent::init();
        $this->_view->assign([
            'fields' => $this->model::getInstance()->fields,
            'validate' => $this->model::getInstance()->validate,
            'search' => $this->model::getInstance()->search,
            'controller' => $this->controller,
        ]);
    }

    public function indexAction()
    {
        if($this->_request->isPost()){
            $page = $this->_request->getPost('page');
            return Common::getInstance()->success($this->model::getInstance()->renderPage($page));
        }
        $this->getView()->display($this->_view->getScriptPath().'/comm/index.phtml');
        return false;
    }

    public function postAction()
    {
        $data = $this->_request->getPost();
        $res = $this->model::getInstance()->renderPost($data);
        if($res){
            return Common::getInstance()->success();
        }
        return Common::getInstance()->error();
    }


    public function deleteAction()
    {
        $id = $this->_request->getPost('id');
        return Common::getInstance()->send($this->model::getInstance()->renderDelete($id));
    }
}