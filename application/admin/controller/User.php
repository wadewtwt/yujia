<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use app\admin\model\UserModel;

use think\Db;
use think\Loader;
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
            $res = db('user')
                ->where('id', $data['id'])
                ->update($data);
            if($res){
                echo json_encode(array('err_msg'=>'修改成功','err_code'=>200,'success'=>'success'));
                exit();
            }else{
                echo json_encode(array('err_msg'=>'修改失败','err_code'=>200,'success'=>'error'));
                exit();
            }
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

        // 地区
        $areaProvince =  Db::query('select id,area_name from area where id%10000=0');
        $this->assign('areaProvince',$areaProvince);

        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);
        $this->assign('info','');
        return $this->fetch('create_update');
    }
}