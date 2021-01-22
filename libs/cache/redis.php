<?php
/**
 * User: tangyijun
 * Date: 2019-11-28
 * Time: 14:30
 */
namespace libs\cache;
use libs\instance;

class Redis extends Instance {

    public $redis;

    public function __construct($name = 'default')
    {
        $this->redis = new \Redis();
        $conf = \Yaf\Application::app()->getConfig()->redis[$name];
        if($conf['pconnect']){
            $this->redis->pconnect($conf['host'], $conf['port'], $conf['timeout']);
        }else{
            $this->redis->connect($conf['host'], $conf['port'], $conf['timeout']);
        }
        if (!empty($conf['auth'])) $this->redis->auth($conf['auth']);
        if($conf['serializer']){
            $this->redis->setOption( \Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP );
            $this->redis->setOption( \Redis::OPT_READ_TIMEOUT,  $conf['timeout'] );
        }
    }
}