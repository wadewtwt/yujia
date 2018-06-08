<?php
namespace app\api\controller;

use app\api\model\CommonModel;
use think\Controller;
use think\Db;

class Practice extends Controller
{
    // 根据用户的openid得到该用户的教育背景列表
    public function getPractice(){
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $info = db('practice')
            ->where(['user_id'=>$userId])
            ->order('end_time desc')
            ->select();
        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到详细教育背景
    public function getPracticeDetail(){
        $id = input('id');
        $info = db('practice')
            ->where(['id'=>$id])
            ->select();
        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 修改教育背景
    public function updatePractice(){

        $id = input('id');
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $where['id'] = $id;
        $where['user_id'] = $userId;

        // 凑成修改数组
        $company = input('company')?:'';
        if($company){
            $data['company'] = $company;
        }
        $position = input('position');
        if($position){
            $data['position'] = $position;
        }
        $data['begin_time'] = input('begin_time');
        $data['end_time'] = input('end_time');

        $res = db('practice')
            ->where($where)
            ->update($data);

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 删除该条教育背景
    public function delPractice(){
        // 组成查找条件
        $id = input('id');
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $where['id'] = $id;
        $where['user_id'] = $userId;

        $res = db('practice')
            ->where($where)
            ->delete();
        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 添加教育背景
    public function addPractice(){

        // 凑足条件
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $data['user_id'] = $userId;
        $data['company'] = input('company');
        $data['position'] = input('position');
        $data['begin_time'] = input('begin_time');
        $data['end_time'] = input('end_time');
        $data['create_time'] = date('Y-m-d H:i:s');
        $res = db('practice')
            ->insert($data);

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }
}
