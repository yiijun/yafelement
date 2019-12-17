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
abstract  class ViewController extends \Base\BaseController
{

    public function init()
    {
        parent::init();
        $view = AbstractModel::getInstance()->renderForm($this->model); //加载视图
        $this->_view->assign(['view' => $view]);
    }


    public function indexAction()
    {

    }

    public function postAction()
    {

    }

    public function putAction()
    {

    }

    public function deleteAction()
    {

    }
}