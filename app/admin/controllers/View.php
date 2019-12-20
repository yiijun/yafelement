<?php
/**
 * User: tangyijun
 * Date: 2019-12-06
 * Time: 15:38
 */

/**
 * Class ViewController
 * 视图类，主要用于通用的渲染视图，单独处理请直接继承\Base\BaseController
 */
use \Libs\Comm\Common;
abstract  class ViewController extends \Base\BaseController
{

    public function init()
    {
        parent::init();
        $view = AbstractModel::getInstance($this->model)->renderForm(); //加载表单
        $this->_view->assign([
            'view' => $view,
            'controller' => $this->controller,
        ]);
    }


    /**
     * @return bool
     * 通用得页面渲染
     */
    public function indexAction()
    {
        if($this->_request->isPost()){
            $page = $this->_request->getPost('page');
            return Common::getInstance()->success(AbstractModel::getInstance($this->model)->renderPage($page));
        }
        $this->getView()->display($this->_view->getScriptPath().'/comm/index.phtml');
        return false;
    }

    /**
     * 通用得post提交方法
     */
    public function postAction()
    {
        $data = $this->_request->getPost();
        $res = AbstractModel::getInstance($this->model)->renderAdd($data);
        if($res){
            return Common::getInstance()->success(AbstractModel::getInstance($this->model)->renderClearForm());
        }
        return Common::getInstance()->error();
    }

    public function putAction()
    {

    }

    public function deleteAction()
    {

    }
}