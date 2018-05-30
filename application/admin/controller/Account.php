<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;

class Account extends Common{
    // 首页
    public function index(){
        $where = [];
        if(request()->isPost()) {
            $title = input('post.title');
            if ($title) {
                $where['title'] = ['like', "%$title%"];
            }
        }

        $info = db('account')
            ->alias('a')
            ->order('a.id desc')
            ->join('user b','a.user_id = b.id')
            ->field('b.name,a.id,a.total,a.balance,a.update_time')
            ->where($where)
            ->paginate(20);

        $res = $info->items();
        $page = $info->render();
        $this->assign('page',$page);
        $this->assign('info',$res);
        return $this->fetch();

    }

    public function del(){
        $id = input('id');
        db('account')->delete($id);
        $this->success('删除成功', 'account/index','','1');
    }
}