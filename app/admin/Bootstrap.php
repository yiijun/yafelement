<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {
        Yaf\Registry::set('config', Yaf\Application::app()->getConfig());
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}

	public function _initView(Yaf\Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
	}

    public function _initError()
    {
        error_reporting(E_ALL);
    }
}
