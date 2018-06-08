<?php
namespace app\api\controller;

use app\api\model\CommonModel;
use think\Controller;
use think\Db;

class Honor extends Controller
{
    // 根据用户的openid得到该用户的教育背景列表
    public function getHonor(){
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $info = db('Honor')
            ->where(['user_id'=>$userId])
            ->order('time desc')
            ->select();
        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到详细教育背景
    public function getHonorDetail(){
        $id = input('id');
        $info = db('Honor')
            ->where(['id'=>$id])
            ->select();
        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 修改教育背景
    public function updateHonor(){

        $id = input('id');
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $where['id'] = $id;
        $where['user_id'] = $userId;

        // 凑成修改数组
        $title = input('title')?:'';
        if($title){
            $data['title'] = $title;
        }

        $data['time'] = input('time');
        $data['pic'] = input('pic');
        $data['create_time'] = date('Y-m-d H:i:s');
        $res = db('Honor')
            ->where($where)
            ->update($data);

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 删除该条教育背景
    public function delHonor(){
        // 组成查找条件
        $id = input('id');
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $where['id'] = $id;
        $where['user_id'] = $userId;

        $res = db('Honor')
            ->where($where)
            ->delete();
        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 添加教育背景
    public function addHonor(){

        // 凑足条件
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $data['user_id'] = $userId;
        $data['title'] = input('title');
        $data['time'] = input('time');
        $data['pic'] = input('pic');
        $data['create_time'] = date('Y-m-d H:i:s');
        $res = db('Honor')
            ->insert($data);

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }
}
