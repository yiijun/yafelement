<?php
/**
 * User: tangyijun
 * Date: 2019-12-27
 * Time: 10:56
 */
class ConfigController extends ViewController
{
    public function indexAction()
    {
        if($this->_request->isPost()){
            $config = ConfigModel::getInstance()->read();
            $res = [];
            foreach ($config as $k => $v){
                $res[$v['key']] = $v['value'];
            }
            return \libs\comm\common::getInstance()->success($res);
        }
    }

    public function postAction()
    {
        $data = $this->_request->getPost();
        foreach ($data as $key => $value){
            ConfigModel::getInstance()->add([$key,$value,$value]);
        }
        return \libs\comm\common::getInstance()->success();
    }
}