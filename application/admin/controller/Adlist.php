<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Request;

class Adlist extends Common{
    // 首页
    public function index(){
        $where = [];
        $info = db('adlist')
            ->where($where)
            ->order('sort desc')
            ->select();
        $this->assign('info',$info);

        return $this->fetch();

    }

    public function update(){
        if(request()->isPost()){
            $data = Request::instance()->post();
            $data['status'] = isset($data['status'])?0:99;
            $res = model('Adlist')->allowField(true)->save($data,['id'=>$data['id']]);
            returnJson($res);
        }
        $id = input('id');
        $info = db('adlist')
            ->find($id);
        $this->assign('info',$info);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);

        return $this->fetch('create_update');
    }

    public function create(){

        if(request()->isPost()){
            $data = Request::instance()->post();
            $data['status'] = isset($data['status'])?0:99;
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = model('Adlist')->allowField(true)->save($data);

            returnJson($res,'新增');
        }
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['create']);

        return $this->fetch('create_update');
    }

    public function del(){
        $id = input('id');
        db('adlist')->delete($id);
        $this->success('删除成功', 'adlist/index','','1');
    }

}