<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;

class Practice extends Common{
    // 首页
    public function index(){
        $where = [];
        if(request()->isPost()) {
            $position = input('post.position');
            if ($position) {
                $where['position'] = ['like', "%$position%"];
            }
        }

        $info = db('practice')
            ->alias('a')
            ->order('a.id desc')
            ->join('user b','a.user_id = b.id')
            ->field('b.name,a.id,a.position,a.company')
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
            $res = model('Practice')->allowField(true)->save($data,['id'=>$data['id']]);
            returnJson($res);
        }
        $id = input('id');
        $info = db('practice')
            ->find($id);
        $this->assign('info',$info);
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['update']);

        return $this->fetch('create_update');
    }

    public function del(){
        $id = input('id');
        db('practice')->delete($id);
        $this->success('删除成功', 'practice/index','','1');
    }

}