<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\CommonModel;
use wechat\WeChat;

class User extends Controller
{
    // 所有人员列表，按字母排序
    public function listAllUser()
    {
        $info = db('user')
            ->where(['status' => 0])
            ->field('id,name,first')
            ->order('first asc')
            ->select();

        if ($info) {
            return jsonReturn('', '', $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到用户信息分页(状态正常)
    public function listUser()
    {
        // 是否付费
        $pay = input('get.pay');
        if($pay){
            $where['pay'] = $pay;
        }
        $where['status'] = 0;

        $info = db('user')
            ->where($where)
            ->order(['recommend'=>'desc'])
            ->order(['id'=>'desc'])
            ->paginate(2);

        $res = $info->items();

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $res);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到用户详细信息
    public function infoUser(){
        $appId = 'wx0faa5945ac1b278f';
        $secret = 'ed495f4332b53eb69b8f391d2ff33a05';
        $code = '023uOnr70GXWaI1TEro70Mjhr70uOnrN';

        $weChat = new WeChat($appId,$secret);
        $tokenOpenid = $weChat->get_access_token($code);
echo "<pre>";
print_r($tokenOpenid);die;
        $id = input('get.id');
        $info = [];
        if($id){
            $where['id'] = $id;
            $where['status'] = 0;
            $info = db('user')
                ->where($where)
                ->find();
            $userId = $info['id'];
            // 教育背景
            $educationInfo = db('education')
                ->where(['user_id'=>$userId])
                ->select();
            // 实践经历
            $practiceInfo = db('practice')
                ->where(['user_id'=>$userId])
                ->select();
            $honorInfo = db('honor')
                ->where(['user_id'=>$userId])
                ->select();

            $info['educations'] = $educationInfo;
            $info['practices'] = $practiceInfo;
            $info['honors'] = $honorInfo;
        }

        if($info){
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        }else{
            return jsonReturn(CommonModel::CODE_401,CommonModel::MSG_401);
        }
    }
}