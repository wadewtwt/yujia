<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;

class WithdrawRecord extends Common{
    // 首页
    public function index(){
        $where = [];
        if(request()->isPost()) {
            $title = input('post.title');
            if ($title) {
                $where['title'] = ['like', "%$title%"];
            }
        }

        $info = db('withdraw_record')
            ->alias('a')
            ->order('a.id desc')
            ->join('user b','a.user_id = b.id')
            ->field('b.name,a.id,a.money,a.status')
            ->where($where)
            ->paginate(20);

        $res = $info->items();
        $page = $info->render();
        $this->assign('page',$page);
        $this->assign('info',$res);
        return $this->fetch();

    }

    public function over(){
        $id = input('id');
        db('withdraw_record')->update(['status'=>20,'id'=>$id]);
        $this->success('修改状态成功', 'withdrawRecord/index','','1');
    }
}