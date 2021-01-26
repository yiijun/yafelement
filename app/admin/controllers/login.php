<?php
/**
 * User: tangyijun
 * Date: 2019-12-30
 * Time: 9:59
 */
use \libs\comm\common;
class LoginController extends \Yaf\Controller_Abstract
{
    public function indexAction()
    {
        return true;
    }

    public function loginAction()
    {
        $username    = $this->getRequest()->getPost('username');
        $password    = $this->getRequest()->getPost('password');
        $remember_me = $this->getRequest()->getPost('remember_me');
        $login = \libs\db\pdo::getInstance()->fetch("SELECT * FROM `yaf_admin` WHERE `username` = ? AND `status` = 1 LIMIT 1", [$username]);
        $expire = $remember_me == 'true' ? 86400 * 3 : 1440;
        if ($login && password_verify($password,$login['pwd'])) {
            $addr = $this->getRequest()->getServer('REMOTE_ADDR'); //登录ip
            $login_time = date('Y-m-d H:i:s');
            $res = \libs\db\pdo::getInstance()->update("UPDATE `yaf_admin` SET `addr` = ?,`login_time` = ? WHERE `username` = ?",[$addr,$login_time,$username]);
            if($res){
                \libs\cache\session::getInstance()->set('login_info', [
                    'id' => $login['id'],
                    'username' => $login['username'],
                    'login_time' => $login_time,
                    'addr' => $addr,
                ],$expire);
                return Common::getInstance()->success([],'恭喜，登录成功');
            }

        }
        return Common::getInstance()->error('login err !');
    }

    public function logoutAction()
    {
        \Yaf\Session::getInstance()->del('login_info');
        $this->redirect("/Login/index");
    }
}