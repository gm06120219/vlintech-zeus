<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\zeus\model\UserModel;
use app\zeus\model\CommunityModel;
use app\zeus\model\UnitModel;
use app\zeus\model\DwellingModel;
use app\common\common\tools;

// 单元管理
class Dwelling extends Basec
{
  // TODO
  public function add()
  {
    // 用info界面，内容全部是空的
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('user/add', 'info');

    $this->assign('step', 'add');
    $this->view->engine->layout('user/layout');
    return $this->fetch('user/info');
  }

  // TODO
  public function addAction()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('user/addAction', 'info');

    $result = array('code' => 10017, 'message' => 'invalid paramters');

    // check paramters
    if (input('?mobile') && input('?password') && input('?nickname') && input('?role')) {
      $userinfo = array(
        'username' => input('username'),
        'mobile'=> input('mobile'),
        'password'=> input('password'),
        'alias'=> input('nickname'),
        'role' => input('role'));
      $user = new UserModel();
      $result = $user->addUser($_SESSION['zeus']['session_token'], $userinfo);
      $result = json_decode($result, true);
    }

    return json_encode($result);
  }

  // TODO
  public function delete()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }

    if (input('?user_id')) {
      $user = new UserModel();
      $result = $user->deleteUser($_SESSION['zeus']['session_token'], input('user_id'));
    } else {
      return $this->error('删除失败，未选择正确的用户', 'user/index');
    }
    return $this->success('删除成功', 'user/index');
  }

  // TODO
  public function deleteAction()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    $result = array('code' => 10017, 'message' => 'invalid paramters');

    if (input('?user_id')) {
      $user = new UserModel();
      $result = $user->deleteUser($_SESSION['zeus']['session_token'], input('user_id'));
      $result_json = json_decode($result, true);
    } else {
      $result = array('code' => 10017, 'message' => 'invalid paramters');
    }

    return $result;
  }

  // TODO
  public function update()
  {
    // 用info界面，内容获取用户详情
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('user/add', 'info');

    if (input('?user_id')) {
      $this->error('获取用户数据失败', '/user/index');
    }

    // 查询用户数据
    $user = new UserModel();
    $result = $user->getUserDetail($_SESSION['zeus']['session_token'], $_GET('user_id'));

    // 将用户数据注册到页面变量中

    $this->assign('step', 'update');
    $this->view->engine->layout('user/layout');
    return $this->fetch('user/info');
  }

  // TODO
  public function find()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    if (input('page')) {
      $page_index = input('page');
    } else {
      $page_index = 1; // 默认显示第一页
    }

    if (!isset($_GET['data'])) {
      return $this->error('请求参数不完整，无法完成查询', 'user/index');
    }
    $page_size = config('paginate')['list_rows'];
    trace('user/index', 'info');
    trace('page_size is: ' . $page_size, 'info');
    trace('page is: ' . $page_index, 'info');

    // 1. API获取用户列表第一页数据，并将数据放至缓存
    // 获取列表并分页显示
    $user = new UserModel();
    $result = $user->findStandardUsers($_SESSION['zeus']['session_token'], $_GET['data'], $page_index, $page_size);
    $result_json = json_decode($result, true);

    // 2. 跳转到对应的view
    if (!$result_json) {
      return $this->error('无法与服务器建立连接，请检查网络!');
    }

    if ($result_json['Rescode'] != 10000) {
      return $this->error('获取用户列表失败, 错误代码' . $result_json['Rescode']);
    }

    $user_list = $result_json['Data']['user_data'];
    $total = $result_json['Data']['total'];

    // 获取分页显示
    $page = Tools::makePageUrl('data='.$_GET['data'], $total, $page_index, $page_size);
    trace("page: ". $page, 'info');

    if (!$user_list) {
      return $this->error('未搜索到相关数据。', 'user/index');
    }
    $this->assign('username', $_SESSION['zeus']['user_name']);
    $this->assign('list', $user_list);
    $this->assign('page', $page);
    $this->view->engine->layout('user/layout');
    return $this->fetch('user/index');
  }

  // find action
  // option with community
  //
  public function findAction()
  {
    session_start();
    if (!$_SESSION) {
      $result = array('code' => 20022, 'message' => 'session token expired');
      return json_encode($result);
    }
    trace('dwelling/findAction', 'info');

    $result = array('code' => 10017, 'message' => 'invalid paramters');

    // check paramters
    if (input('?option')) {
      switch (input('option')) {
        case 'unit':
          if (input('?community_id') && input('?unit_id')) {
            $dwelling = new DwellingModel();
            $result = $dwelling->getUnitDwellings($_SESSION['zeus']['session_token'], input('community_id'), input('unit_id'));
            $result = json_decode($result, true);
          }
          break;

        default:
          # code...
          break;
      }
    }

    return json_encode($result);
  }

  // TODO
  public function detail()
  {
    // 用info界面，内容是预填充的，并且有部分内容不可修改，需要lock住
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    if (!isset($_GET['user_id'])) {
      return $this->error('请求参数不完整，无法完成查询', 'manuser/index');
    }
    trace('user/detail', 'info');
    trace('id: ' . $_GET['user_id'], 'info');

    $user = new UserModel();
    $result = $user->getUserDetail($_SESSION['zeus']['session_token'], $_GET['user_id']);

    $result_json = json_decode($result, true);
    if ($result_json['Rescode'] == 10000) {
      $data_json = $result_json['Data'];
      // echo json_encode($data_json);

      $this->assign('username', $data_json['username']);
      $this->assign('alias', $data_json['alias']);
      $this->assign('mobile', $data_json['mobile']);
      $this->assign('house_list', []);
    } else {
      return $this->error('获取用户数据失败', 'user/index');
    }

    $com = new CommunityModel();
    $result = $com->getCommunities($_SESSION['zeus']['session_token'], 1, 1000);
    $result_json = json_decode($result, true);
    if ($result_json['Rescode'] == 10000) {
      $data_json = $result_json['Data'];
      // echo json_encode($data_json);
      // echo gettype($data_json['communities']);

      $this->assign('communities', $data_json['communities']);
    } else {
      return $this->error('获取社区数据失败', 'user/index');
    }

    // $dwelling = new DwellingModel();
    // $result = $dwelling->getUsersDwellings($_SESSION['zeus']['session_token'], $_GET['user_id']);
    // $result_json = json_decode($result, true);
    // if ($result_json['Rescode'] == 10000) {
    //   $data_json = $result_json['Data'];
    //   // echo json_encode($data_json);
    //   echo gettype($data_json['communities']);
    //
    //   $this->assign('dwellings', $data_json['communities']);
    // } else {
    //   return $this->error('获取用户住房信息失败', 'user/index');
    // }

    $this->view->engine->layout('user/layout');
    return $this->fetch('user/detail');
  }

  // TODO
  public function index()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    if (input('page')) {
      $page_index = input('page');
    } else {
      $page_index = 1; // 默认显示第一页
    }
    $page_size = config('paginate')['list_rows'];
    trace('user/index', 'info');
    trace('page_size is: ' . $page_size, 'info');
    trace('page is: ' . $page_index, 'info');

    // 1. API获取用户列表第一页数据，并将数据放至缓存
    // 获取列表并分页显示
    $user = new UserModel();
    $result = $user->getUsers($_SESSION['zeus']['session_token'], $page_index, $page_size);
    $result_json = json_decode($result, true);

    // 2. 跳转到对应的view
    if (!$result_json) {
      return $this->error('无法与服务器建立连接，请检查网络!');
    }

    if ($result_json['Rescode'] != 10000) {
      return $this->error('获取用户列表失败, 错误代码' . $result_json['Rescode']);
    }

    $user_list = $result_json['Data']['user_data'];
    $total = $result_json['Data']['total'];

    // 获取分页显示
    $page = Tools::makePage($total, $page_index, $page_size);
    trace("page: ". $page, 'info');

    if (!$user_list) {
      return $this->error('用户列表为空，请检查服务器数据。');
    }
    $this->assign('username', $_SESSION['zeus']['user_name']);
    $this->assign('list', $user_list);
    $this->assign('page', $page);
    $this->view->engine->layout('user/layout');
    return $this->fetch('user/index');
  }
}
