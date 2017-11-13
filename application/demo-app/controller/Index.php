<?php
namespace app\index\controller;

use \think\controller;

class Index extends Controller
{
    public function index()
    {
        $ll = array('demo1', 'demo2', 'demo3');
        $this->assign('list', $ll);
        $this->assign('demo', 'Demo String');
        return $this->fetch('index');
    }
}
