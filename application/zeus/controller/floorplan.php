<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\zeus\model\FloorplanModel;
use app\zeus\model\CommunityModel;
use app\common\common\tools;

// 户型管理
class Floorplan extends Basec
{
  // TODO 添加
  public function add()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('Manfloorplan/add', 'info');


    // 1. API获取用户列表第一页数据，并将数据放至缓存
    // 获取列表并分页显示
    $com = new CommunityModel();
    $result = $com->getCommunities($_SESSION['zeus']['session_token'], 1, 1000);
    if (!$result) {
      // TODO show error message web view
    }
    $result_json = json_decode($result, true);
    if (!$result_json || $result_json['Rescode'] != 10000) {
      // TODO show error message
    }
    if (!$result_json['Data'] || $result_json['Data']['total'] <= 0) {
      // TODO show error message
    }
    $communities = $result_json['Data']['communities'];
    // echo json_encode($communities);

    // 注册页面所需参数
    $this->assign('step', 'add');
    $this->assign('communities', $communities);

    $this->view->engine->layout('floorplan/layout');
    return $this->fetch('floorplan/detail');
  }

  public function upload()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('Manfloorplan/upload', 'info');

    if (input('?get.floorplan_id') && input('?get.step')) {
      $floorplan = new Floorplan();
      $result = $floorplan->detail($_SESSION['zeus']['session_token'], input('get.floorplan_id'));
      $result =  json_decode($result, true);
      if ($result['Rescode'] == 10000) {
        $this->assign('floorplan_name', $result['Data']['floor_plan'][0]['name']);
        $this->assign('community_id', $result['Data']['floor_plan'][0]['community_id']);
        $com = new CommunityModel();
        $result = $com->detail($_SESSION['zeus']['session_token'], $result['Data']['floor_plan'][0]['community_id']);
        $this->assign('result', $result);
        $result =  json_decode($result, true);
        if ($result['Rescode'] == 10000) {
          $this->assign('community_name', $result['Data']['communities'][0]['community_name']);
        } else {
          return $this->error('获取社区信息失败', './index');
        }
      } else {
        return $this->error('获取户型信息失败', './index');
      }
    } else {
      return $this->error('参数缺失', './index');
    }

    $this->assign('step', input('get.step'));
    $this->view->engine->layout('floorplan/layout');
    return $this->fetch('floorplan/upload');
  }

  // TODO 删除
  public function delete()
  {
  }

  // TODO 修改
  public function update()
  {
    //
  }

  // TODO 查找
  public function find()
  {
    // 还是用index界面，但是内容是find后的
  }

  // TODO 详情页
  public function detail()
  {
  }

  // 列表
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
    trace('Manfloorplan/index', 'info');
    trace('page_size is: ' . $page_size, 'info');
    trace('page is: ' . $page_index, 'info');

    // 1. API获取用户列表第一页数据，并将数据放至缓存
    // 获取列表并分页显示
    $floorplan = new FloorplanModel();
    $result = $floorplan->getFloorplans($_SESSION['zeus']['session_token'], $page_index, $page_size);
    $result_json = json_decode($result, true);

    // 2. 跳转到对应的view
    if (!$result_json) {
      return $this->error('无法与服务器建立连接，请检查网络!');
    }

    if ($result_json['Rescode'] != 10000) {
      return $this->error('获取户型列表失败, 错误代码' . $result_json['Rescode']);
    }

    $floorplan_list = $result_json['Data']['floor_plan'];
    $total = $result_json['Data']['total'];

    // 获取分页显示
    $page = Tools::makePage($total, $page_index, $page_size);
    trace("page: ". $page, 'info');

    if (!$floorplan_list) {
      return $this->error('户型列表为空，请检查服务器数据。');
    }

    $this->assign('username', $_SESSION['zeus']['user_name']);
    $this->assign('list', $floorplan_list);
    $this->assign('page', $page);

    $this->view->engine->layout('floorplan/layout');
    return $this->fetch('floorplan/index');
  }

  // 添加操作
  public function add_action()
  {
    session_start();
    if (!$_SESSION) {
      return $this->error('登录超时，请重新登录', '/');
    }
    trace('ActionFloorplan/add', 'info');
    $arrayName = array('code' => 20000, 'data' => 'response data', 'msg' => 'error message');

    // get paramter
    if (!input('?post.community_id') || !input('?post.floorplan_name')) {
      $arrayName = array('code' => 10017, 'data' => '', 'msg' => 'missing paramters');
      echo json_encode($arrayName);
      return;
    }
    $community_id = input('post.community_id');
    $floorplan_name = input('post.floorplan_name');;

    // TODO paramters validity check

    // new floorplan model
    $floorplan = new FloorplanModel();
    // call floorplan model's add method
    $result = $floorplan->add($_SESSION['zeus']['session_token'], $community_id, $floorplan_name);
    $result_array = json_decode($result, true);
    $result_array['data'] = json_encode(array('session_token' => $_SESSION['zeus']['session_token'], 'community_id' => $community_id, 'floorplan_name' => $floorplan_name));
    echo json_encode($result_array);
  }
}
