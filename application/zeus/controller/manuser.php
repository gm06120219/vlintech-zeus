<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\zeus\model\user;
use app\common\common\tools;

// 用户管理
class Manuser extends Basec
{
    // TODO
    public function add()
    {
        // 用info界面，内容全部是空的
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        trace('Manuser/add', 'info');

        $this->assign('step', 'add');
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/info');
    }

    // TODO 删除用户
    public function delete()
    {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }

        // 1. API获取用户详情
        // $user = new User();
        // $result = $user->getUsers($_SESSION['zeus']['session_token'], );

        // $this->view->engine->layout('user/layout');
        $result = array('code' => 10001, 'message' => 'response');
        return $result;
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
        // 用info界面，内容是预填充的，并且有部分内容不可修改，需要lock住
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (!isset($_GET['user_id'])) {
            return $this->error('请求参数不完整，无法完成查询', 'manuser/index');
        }
        trace('Manuser/detail', 'info');
        trace('id: ' . $_GET['user_id'], 'info');

        // 1. API获取用户详情
        // $user = new User();
        // $result = $user->getUsers($_SESSION['zeus']['session_token'], );

        $this->view->engine->layout('user/layout');
        return $this->fetch('user/detail');
    }

    // 用户列表
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
        trace('Manuser/index', 'info');
        trace('page_size is: ' . $page_size, 'info');
        trace('page is: ' . $page_index, 'info');

        // 1. API获取用户列表第一页数据，并将数据放至缓存
        // 获取列表并分页显示
        $user = new User();
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
