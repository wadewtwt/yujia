<?php
namespace app\api\controller;

use think\Controller;

class Index extends Controller
{
    // 首页
    public function index()
    {
        $data = [
            'code' => 201111,
            'msg' => 'success',
            'data' => [
                'id' => '13',
                'name' => '老王'
            ]
        ];
        return json_encode($data);
    }

    public function del()
    {
        $id = input('id');
        db('credit')->delete($id);
        $this->success('删除成功', 'credit/index', '', '1');
    }
}