<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Config;
// 应用公共文件
// 修改用户密码
function userEncrypt($data){
    $str = Config::get('project_name');
    return md5($data.$str);
}

// 返回json信息
function returnJson($res){
    if($res){
        echo json_encode(array('err_msg'=>'修改成功','err_code'=>200,'success'=>'success'));
        exit();
    }else{
        echo json_encode(array('err_msg'=>'修改失败','err_code'=>200,'success'=>'error'));
        exit();
    }
}