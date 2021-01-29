<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {
        Yaf\Registry::set('config', Yaf\Application::app()->getConfig());
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {

	}

	public function _initView(Yaf\Dispatcher $dispatcher){

	}

    public function _initError()
    {
        error_reporting(E_ALL);
    }

    public function _initAutoload(Yaf\Dispatcher $dispatcher) {
        require __DIR__.'/../../vendor/autoload.php';
    }
}
