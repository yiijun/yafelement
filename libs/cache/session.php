<?php
/**
 * User: tangyijun
 * Date: 2019-11-28
 * Time: 14:58
 */
namespace libs\cache;
use libs\instance;

class Session extends Instance
{
    public function set($name,$data,$lifetime = 60)
    {
        ini_set('session.gc_maxlifetime',  $lifetime);
        ini_set('session.cookie_lifetime', $lifetime);
        \Yaf\Session::getInstance()->start(); //开启会话
        setcookie(session_name(),session_id(),time()+($lifetime),'/');
        $time = time() + $lifetime;
        switch ($data){
            case is_array($data):
                $data['expire'] = $time;
                break;
            case is_string($data):
                $data = $time.'<>'.$data;
                break;
            default:
                $data = $time.'<>'.$data;
                break;
        }
        $res = \Yaf\Session::getInstance()->set($name,$data);
        if($res){
            return true;
        }
    }

    public function get($name)
    {
        $session_info = \Yaf\Session::getInstance()->get($name);
        if(is_array($session_info)){
            if(time() > $session_info['expire']){
                \Yaf\Session::getInstance()->del($name);
            }
            unset($session_info['expire']);
        }

        if(is_string($session_info)){
            $arr = explode('<>',$session_info);
            if(empty($arr)){
                throw new \Exception('获取 session 格式错误',-1);
            }
            if(time() > $arr[0]){
                //session过期删除
                \Yaf\Session::getInstance()->del($name);
            }
            $session_info = $arr[1];
        }
        return $session_info;
    }
}