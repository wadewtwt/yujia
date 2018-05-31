<?php
namespace app\api\model;

use think\Model;

class CommonModel extends Model{
    const CODE_200 = 200;
    const CODE_404 = 404;
    const CODE_401 = 401;

    const MSG_200 = '成功';
    const MSG_404 = '没有数据';
    const MSG_401 = '该用户不存在或已被禁用';
}