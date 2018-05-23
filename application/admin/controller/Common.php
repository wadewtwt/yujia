<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;

class Common extends Controller{
    public function __construct()
    {
        // 必须要有
        parent::__construct();

        $loginAdmin = Session::get('login_admin');
        if($loginAdmin == null){
            $this->error('请登陆', 'login/index', '', 0);
        }
    }
}