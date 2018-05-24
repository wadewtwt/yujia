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

    public function update(){

        if($_POST){
echo 434433;die;
            return json_encode(array('err_msg'=>'新增成功','err_code'=>200,'success'=>'success'));
            exit();

        }

        return $this->fetch('create_update');
    }
}