<?php
namespace app\zeus\controller;

use think\Session;
use \think\Controller;

class Index extends Basec
{
    // 显示首页
    public function index()
    {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        $this->assign('username', $_SESSION['zeus']['user_name']);
        $this->view->engine->layout('index/layout');
        return $this->fetch('index');
    }
}
