<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;

class Honor extends Common{
    // 首页
    public function index(){
        $where = [];
        if(request()->isPost()) {
            $title = input('post.title');
            if ($title) {
                $where['title'] = ['like', "%$title%"];
            }
        }
        $info = db('honor')
            ->alias('a')
            ->order('a.id desc')
            ->join('user b','a.user_id = b.id')
            ->field('b.name,a.id,a.title,a.time,a.pic')
            ->where($where)
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
            $res = model('Honor')->allowField(true)->save($data,['id'=>$data['id']]);
            returnJson($res);
        }
        $id = input('id');
        $info = db('honor')
            ->find($id);
        $this->assign('info',$info);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);

        return $this->fetch('create_update');
    }

    public function del(){
        $id = input('id');
        db('honor')->delete($id);
        $this->success('删除成功', 'honor/index','','1');
    }

}