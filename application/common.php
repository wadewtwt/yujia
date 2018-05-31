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

// 后台返回json信息
function returnJson($res,$title='修改'){
    if($res){
        echo json_encode(array('err_msg'=>$title.'成功','err_code'=>200,'success'=>'success'));
        exit();
    }else{
        echo json_encode(array('err_msg'=>$title.'失败','err_code'=>200,'success'=>'error'));
        exit();
    }
}

// 前端返回json到前端
// 200
function jsonReturn($code=200,$msg='成功',$data=''){
    $json = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    return json_encode($json);
}