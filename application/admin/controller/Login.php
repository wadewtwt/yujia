<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        if ($_POST) {
            $name = input('post.name');
            $password = input('post.password');
            if (!$name) {
                $this->error('用户名不能为空');
            }
            if (!$password) {
                $this->error('密码不能为空');
            }
            $info = db('admin')->where('name', $name)->find();
            if (!$info) {
                $this->error('用户不存在');
            }
            if (md5($password) != $info['password']) {
                $this->error('密码不正确');
            }else {
                Session::set('login_admin',$info);
                //记录登录信息
                $ip = request()->ip();
                Db::table('admin')->where('id', $info['id'])->update([
                    'ip' => $ip,
                    'update_time' => date('Y-m-d H:i:s'),
                ]);
                $this->success('登入成功', 'index/index');
            }
        }
        return $this->fetch();
    }
}
