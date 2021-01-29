<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {

    }

	public function _initRoute(Yaf\Dispatcher $dispatcher) {

	}

	public function _initView(Yaf\Dispatcher $dispatcher){

	}

    public function _initAutoload(Yaf\Dispatcher $dispatcher) {
        require __DIR__.'/../../vendor/autoload.php';
    }
}
