<?php
namespace app\api\controller;

use app\api\model\CommonModel;
use think\Controller;
use wechat\WeChat;

class Index extends Controller
{
    // 第一次加载所要获得到的信息
    public function firstLoad(){

        $appId = input('appid');
        $appKey = input('appkey');
        $code = input('code');

        $weChat = new WeChat($appId,$appKey);
        // 获得access_token
        $accessToken = $weChat->getAccessToken($code);

        $accessToken = isset($accessToken['access_token']) ? $accessToken['access_token'] : '';

        if($accessToken == null){
            return jsonReturn(CommonModel::CODE_404,'未有access_token');
        }
        // 获得openid
        $openId = $weChat->getOpenId($code);
        $openId = isset($openId['openid']) ? $openId['openid'] : '';
        if($openId == null){
            return jsonReturn(CommonModel::CODE_404,'未有openid');
        }
        // 写入到user表
        $userCount = db('user')
            ->where(['openid'=>$openId])
            ->count();
        if(!$userCount){
            $data = ['openid' => $openId];
            db('user')->insert($data);
        }

        $info  = [
            'access_token' => $accessToken,
            'openid' => $openId
        ];
        return jsonReturn(CommonModel::CODE_200,CommonModel::MSG_200,$info);

    }

    // 首页banner
    public function getTopImg()
    {
        $info = db('adlist')
            ->where(['status' => 0])
            ->field('id,title,img,sort')
            ->order('sort desc')
            ->limit(3)
            ->select();
        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

}