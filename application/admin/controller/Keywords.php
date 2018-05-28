<?php
namespace app\admin\controller;

use app\admin\model\CommonModel;
use think\Paginator;
use think\Request;
use think\Db;

class Keywords extends Common{
    // 首页
    public function index(){
        $where = [];
        if(request()->isPost()) {
            $title = input('post.title');
            if ($title) {
                $where['title'] = ['like', "%$title%"];
            }
            $recommend = input('post.recommend');
            if($recommend==1){
                $where['recommend'] = $recommend;
            }elseif($recommend==2){
                $where['recommend'] = 0;
            }
        }
        $info = db('keywords')
            ->alias('a')
            ->order('a.id desc')
            ->join('keywords_user b','a.id = b.keywords_id')
            ->field('b.user_id,a.id,a.title,a.recommend')
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
            $res = model('Keywords')->allowField(true)->save($data,['id'=>$data['id']]);
            returnJson($res);
        }
        $id = input('id');
        $info = db('keywords')
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
            // 找下一个id，排除高并发
            $lastId = db('keywords')->order('id desc')->limit(1)->column('id');
            $lastId = intval($lastId[0]+1);
            Db::startTrans();
            try{
                $data['create_time'] = date('Y-m-d H:i:s');
                db('keywords')->insert($data);
                // 表keywords_user
                db('keywords_user')->insert(['keywords_id'=>$lastId]);
                Db::commit();
                returnJson($res=1,'新增');
            }catch(\Exception $e){
                Db::rollback();
                returnJson($res='','新增');
            }
        }
        // 方便url和标头
        $action = CommonModel::$action;
        $this->assign('action',$action['create']);

        return $this->fetch('create_update');
    }

    public function del(){
        $id = input('id');
        db('keywords')->delete($id);
        $this->success('删除成功', 'keywords/index','','1');
    }

}