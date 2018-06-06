<?php
namespace app\api\model;

use think\Model;
use think\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class CommonModel extends Model
{
    const CODE_200 = 200;
    const CODE_404 = 404;
    const CODE_401 = 401;
    const CODE_403 = 403;

    const MSG_200 = '成功';
    const MSG_404 = '没有数据';
    const MSG_401 = '该用户不存在或已被禁用';
    const MSG_403 = '没有权限';

    // 用于给处理数组
    const LETTER = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    // 上传单张图片，文件名不变
    public static function upload($path)
    {
        // 图片的本地路径
//        $file = $_FILES['myfile']['tmp_name'];
        $file = $path;
        // 制作文件名
        $pathInfo = pathinfo($file);

        $key = $pathInfo['basename'];
        // 读取配置
        $qiniuConfig = Config::get('qiniu');
        Vendor('gmars.tp5-qiniu.qiniu_driver.autoload');
        // 初始化签权对象
        $auth = new Auth($qiniuConfig['accesskey'], $qiniuConfig['secretkey']);
        // 生成上传Token
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        $uploadMgr = new UploadManager();
        // 1.token 2.加密后的文件名 3.文件所在地方
        list($ret,$err) = $uploadMgr->putFile($token,$key,$file);
        $imgUrl = $qiniuConfig['domain'].'/'.$ret['key'];
        return $imgUrl;
    }

    public static function getUserId($openId){
        $userId = db('user')
            ->where(['openid'=>$openId])
            ->field('id')
            ->find();
        return $userId;
    }
}