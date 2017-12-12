<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\common\common\tools;
use app\zeus\model\oss;
use org\util\ArrayList;
use aliyun\oss_sts\Sts;

class Demo extends Basec
{
  public function index()
  {
    $this->view->engine->layout('demo/layout');
    $this->assign('hostname', $_SERVER['SERVER_NAME']);
    return $this->fetch('demo/index');
  }

  public function token()
  {
    $oss = new Oss();
    $result = $oss->getToken();
    $result_json = json_decode($result, true);
    return $result_json;
  }

  public function tlist()
  {
    $list     = ['thinkphp', 'thinkphp', 'onethink', 'topthink'];
    $arrayList  = new ArrayList($list);
    $arrayList->add('kancloud');
    $arrayList->unique();
    dump($arrayList->toArray());
    echo $arrayList->toJson();
  }

  public function tsts()
  {
    $sts = new Sts("config.json");
    // echo $sts->getSession();
  }

  public function socket()
  {
    $this->view->engine->layout('demo/layout');
    $this->assign('hostname', $_SERVER['SERVER_NAME']);
    return $this->fetch('demo/socket');
  }

  public function chat_demo()
  {
    // $this->view->engine->layout('demo/layout');
    $this->assign('hostname', $_SERVER['SERVER_NAME']);
    return $this->fetch('demo/chat_demo');
  }

  public function chat()
  {
    $this->view->engine->layout('demo/layout');
    $this->assign('hostname', $_SERVER['SERVER_NAME']);
    return $this->fetch('demo/chat');
  }

  public function test()
  {
    return $this->fetch('demo/test');
  }

  public function trequest()
  {
    echo input('role') + '  ';
    echo input('nickname') + '  ';
  }
}
