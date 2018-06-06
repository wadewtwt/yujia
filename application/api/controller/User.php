<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\CommonModel;
use think\Db;
use wechat\WeChat;
use think\Request;

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

        $letter = CommonModel::LETTER;
        $UsersInfo = [];
        foreach ($info as $k => $v) {
            foreach ($letter as $k1 => $v1) {
                if ($v['first'] == $v1) {
                    $UsersInfo[$k1]['first'] = $v1;
                    $UsersInfo[$k1]['content'][] = $v;
                }
            }
        }

        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $UsersInfo);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到用户信息分页(状态正常)
    public function listUser()
    {
        $pageSize = input('pagesize');
        // 是否付费
        $pay = input('pay');
        if ($pay) {
            $where['pay'] = $pay;
        } else {
            $where['pay'] = 0;
        }
        $where['status'] = 0;

        $info = db('user')
            ->where($where)
            ->order(['recommend' => 'desc'])
            ->order(['id' => 'desc'])
            ->paginate($pageSize);

        $res = $info->items();
        foreach ($res as $key => $val) {
            $areaInfo = db('area')->where(['id' => $val['area_id']])->field('area_name')->find();
            $res[$key]['area_id'] = $areaInfo['area_name'];
        }

        if ($res) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $res);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 得到用户详细信息
    public function infoUser()
    {
        $appId = 'wx0faa5945ac1b278f';
        $secret = 'ed495f4332b53eb69b8f391d2ff33a05';
//        // 根据传过来的code找用户
//        $code = input('code');
//        $weChat = new WeChat($appId, $secret);
//        $openIdSessionKey = $weChat->getOpenId($code);
//        $openId = isset($openIdSessionKey['openid']) ? $openIdSessionKey['openid'] : '';
//        if (!$openId) {
//            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
//        }
//        $where['openid'] = $openId;

        $id = input('id');
        $where['id'] = $id;
        $info = db('user')
            ->where($where)
            ->select();
        $info = $info[0];
        // 地区换上
        $areaInfo = db('area')->where(['id' => $info['area_id']])->field('area_name')->find();
        $info['area_id'] = $areaInfo['area_name'];
        // 技能水平
        $info['skill'] = explode('+', $info['skill']);
        // 判断有没有该用户或者该用户被禁用
        if (!$info || $info['status'] == 99) {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        } else {
            $userId = $info['id'];
            // 教育背景
            $educationInfo = db('education')
                ->where(['user_id' => $userId])
                ->select();
            // 实践经历
            $practiceInfo = db('practice')
                ->where(['user_id' => $userId])
                ->select();
            // 获得荣誉
            $honorInfo = db('honor')
                ->where(['user_id' => $userId])
                ->select();

            // 关键词
            $keywordsUser = db('keywords_user')
                ->where(['user_id' => $userId])
                ->select();
            foreach ($keywordsUser as $k => $v) {
               $keywordsArr[] = $v['keywords_id'];
            }
            $keywordsStr = implode(',',$keywordsArr);
            $keywords = Db::query('select title from keywords where id in('.$keywordsStr.")");

            $info['keywords'] = $keywords;
            $info['educations'] = $educationInfo;
            $info['practices'] = $practiceInfo;
            $info['honors'] = $honorInfo;
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        }
    }

    // 获得小程序分享二维码
    public function shares()
    {
        $appId = 'wx0faa5945ac1b278f';
        $secret = 'ed495f4332b53eb69b8f391d2ff33a05';
        $code = input('code');

        $weChat = new WeChat($appId, $secret);
        // 获得openid
        $openId = $weChat->getOpenId($code);
        $openId = isset($openId['openid']) ? $openId['openid'] : '';
        // 如果code能换回openid
        if ($openId) {
            $qrcode = db('user')
                ->field('qrcode')
                ->where('openid', $openId)
                ->find();
            // 如果没有生成过小程序码
            if ($qrcode['qrcode']) {
                return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $qrcode);
            } else {
                // 获得access_key
                $accessTokenOpenid = $weChat->getAccessToken($code);
                $accessToken = $accessTokenOpenid['access_token'];
                // 获得小程序吗，这个是数组
                $info = $weChat->getWxacode($accessToken, 'pages/index?query=' . $openId);
                $info = $info[1];
                //保存地址
                $imgDir = 'img/qrcode/';
                //要生成的新图片名字
                $filename = md5(time() . mt_rand(10, 99)) . ".png";
                // 组成路径加名称
                $newFilePath = $imgDir . $filename;
                $res = file_put_contents($newFilePath, $info);
                $qiniuUrl = CommonModel::upload($newFilePath);
                if ($res) {
                    db('user')
                        ->where('openid', $openId)
                        ->update(['qrcode' => $qiniuUrl]);
                }
                return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $qiniuUrl);
            }
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 搜索
    public function searchUser()
    {
        $name = input('name');
        $id = input('id');
        if ($id) {
            $where['id'] = $id;
        } elseif ($name) {
            $where['name'] = ['like', '%' . $name . '%'];
        }

        $info = db('user')
            ->where($where)
            ->select();

        foreach ($info as $key => $val) {
            $areaInfo = db('area')->where(['id' => $val['area_id']])->field('area_name')->find();
            $info[$key]['area_id'] = $areaInfo['area_name'];
        }

        if ($info) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $info);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 写入数据
    public function changeInfo(){
        $request = Request::instance();
        $postInfo = $request->post();

        
        echo "<pre>";
        print_r($postInfo);die;
    }
}