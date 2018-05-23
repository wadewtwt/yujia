<?php
namespace app\admin\controller;

use think\Loader;

class User extends Common{
    public function index(){
        $info = db('user')
            ->where('status',0)
            ->order('id desc')
            ->select();

        $this->assign('info', $info);
        return $this->fetch();
    }
}