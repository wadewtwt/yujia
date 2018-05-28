<?php
namespace app\admin\controller;

use app\admin\controller\Common;
use think\Session;

class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    // 退出登录
    public function loginOut()
    {
        Session::delete('login_admin');
        $this->success('登入成功', 'login/index','',1);
    }
}
