<?php
namespace app\admin\controller;

use app\admin\controller\Common;
use think\Session;

class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function lists()
    {
        return $this->fetch();
    }
}
