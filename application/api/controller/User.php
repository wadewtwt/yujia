<?php
namespace app\api\controller;

use think\Controller;

class User extends Controller{
    public function listAllUser(){
        $info = db('user')
            ->where(['status'=>0])
            ->field('id,name,first')
            ->order('first asc')
            ->select();
        echo "<pre>";
        print_r($info);die;
    }
}