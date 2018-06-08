<?php
namespace app\api\controller;

use think\Controller;
use gmars\qiniu\Qiniu;
use think\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Loader;
/**
 * 管理员
 */
class Upload extends Controller {

    // 小程序获取uptoken
    public function uptoken(){
        // 读取配置
        $qiniuConfig = Config::get('qiniu');
        Vendor('gmars.tp5-qiniu.qiniu_driver.autoload');
        // 初始化签权对象
        $auth = new Auth($qiniuConfig['accesskey'], $qiniuConfig['secretkey']);
        // 生成上传Token
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        $info = [
            'uptoken' => $token
        ];
        return json_encode($info);
    }

}