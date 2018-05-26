<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;

class Education extends Common{
    // 首页
    public function index(){

        $info = db('education')
            ->alias('a')
            ->order('a.id desc')
            ->join('user b','a.user_id = b.id')
            ->field('b.name,a.id,a.college,a.major,a.education')
            ->paginate(20);

        $res = $info->items();
        $page = $info->render();
        $this->assign('page',$page);
        $this->assign('info',$res);

        return $this->fetch();

    }

    public function update(){
        if(request()->isPost()){
            $data = Request::instance()->post();
            $res = model('Education')->allowField(true)->save($data,['id'=>$data['id']]);
            returnJson($res);
        }
        $id = input('id');
        $info = db('education')
            ->find($id);
        $this->assign('info',$info);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);

        return $this->fetch('create_update');
    }

    public function del(){
        $id = input('id');
        db('education')->delete($id);
        $this->success('删除成功', 'education/index','','1');
    }

}