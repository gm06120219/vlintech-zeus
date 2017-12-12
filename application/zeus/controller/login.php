<?php
namespace app\zeus\controller;

use think\Controller;
use think\Log;
use think\Session;
use app\zeus\model\TokenModel;

class Login extends Basec
{
  // 显示login界面
  public function index()
  {
    return $this->fetch('index');
  }

  // 点击登录后执行login操作
  // public function login($username='', $password='')
  public function login()
  {
    if (!input('?post.username') || !input('?post.password')) {
      return $this->error('登录失败, 缺少用户名或密码');
    }
    session_start();
    $username = input('post.username');
    $password = input('post.password');
    $token = new TokenModel();
    $result = $token->getToken($username, $password);
    $result_json = json_decode($result, true);

    if (!$result_json) {
      return $this->error('网络连接异常');
    }

    if ($result_json['Rescode'] != 10000) {
      return $this->error('登录失败, 错误代码' . $result_json['Rescode']);
    }

    $token = $result_json['Data']['session_token'];
    $userid = $result_json['Data']['user_id'];
    $username = $result_json['Data']['username'];
    $usertype = $result_json['Data']['type'];

    if (!$token) {
      return $this->error('登录失败, 未经许可。');
    }

    $_SESSION['zeus']['user_id'] = $userid;
    $_SESSION['zeus']['user_name'] = $username;
    $_SESSION['zeus']['user_type'] = $usertype;
    $_SESSION['zeus']['session_token'] = $token;

    return $this->success('登录成功', 'index/index');
  }
}
