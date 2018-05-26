<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Db;
use think\Request;

class User extends Common{
    // 用户管理-首页
    public function index(){

        $info = db('user')
            ->order('id desc')
            ->select();
        $this->assign('info', $info);
        return $this->fetch();
    }

    // 更新
    public function update(){
        if(request()->isPost()){
            $data = Request::instance()->post();
            $data['recommend'] = isset($_POST['recommend'])?:0;
            $res = model('User')->allowField(true)->save($data,['id'=>$data['id']]);

            returnJson($res);
        }
        // 查出该信息
        $id = input('id');
        $info = Db('user')->find($id);
        $this->assign('info',$info);
        // 地区
        $areaProvince =  Db::query('select id,area_name from area where id%10000=0');
        $this->assign('areaProvince',$areaProvince);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);

        return $this->fetch('create_update');
    }

    public function create(){

        if(request()->isPost()){
            $data = Request::instance()->post();
            $data['recommend'] = isset($_POST['recommend'])?:0;
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = model('User')->allowField(true)->save($data);

            returnJson($res);
        }
        // 地区
        $areaProvince =  Db::query('select id,area_name from area where id%10000=0');
        $this->assign('areaProvince',$areaProvince);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['create']);

        return $this->fetch('create_update');
    }

    // 修改密码
    public function update_password(){
        if(request()->isPost()){
            $data = Request::instance()->post();
            $data['password'] = userEncrypt($data['password']);
            $res = model('User')->allowField(true)->save($data,['id'=>$data['id']]);

            returnJson($res);
        }
        $id = input('id');
        $this->assign('id',$id);

        return $this->fetch();
    }
}