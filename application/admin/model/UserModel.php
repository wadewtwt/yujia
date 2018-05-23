<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class UserModel extends Model{
    public function getList(){
        $where['name'] = '詹姆斯';
        $result = Db::table('user')->where($where)->select();
        return $result;
    }
}