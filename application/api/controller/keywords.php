<?php
namespace app\api\controller;

use app\api\model\CommonModel;
use think\Controller;
use think\Db;

class Keywords extends Controller
{

    public function getUserKeywords()
    {
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $keywordsUser = db('keywords_user')
            ->where(['user_id' => $userId])
            ->select();
        foreach ($keywordsUser as $k => $v) {
            $keywordsArr[] = $v['keywords_id'];
        }
        $keywordsStr = implode(',', $keywordsArr);
        $keywords = Db::query('select id,title from keywords where id in(' . $keywordsStr . ")");

        if ($keywords) {
            return jsonReturn(CommonModel::CODE_200, CommonModel::MSG_200, $keywords);
        } else {
            return jsonReturn(CommonModel::CODE_404, CommonModel::MSG_404);
        }
    }

    // 删除关键词
    public function delKeyword()
    {
        $kid = input('kid');
        $openId = input('openid');
        $commonModel = new CommonModel();
        $userId = $commonModel::getUserId($openId);
        $userId = $userId['id'];
        $where['user_id'] = $userId;
        $where['keywords_id'] = $kid;
        $res1 = db('keywords_user')
            ->where($where)
            ->delete();
        if($res1){
            $kWhere['id'] = $kid;
            $res2 = db('keywords')
                ->where($kWhere)
                ->delete();
            if($res2){
                return jsonReturn(CommonModel::CODE_200,CommonModel::MSG_200);
            }else{
                return jsonReturn(CommonModel::CODE_403,CommonModel::MSG_403);
            }
        }

    }
}
