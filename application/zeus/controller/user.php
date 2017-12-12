<?php
namespace app\zeus\controller;

use app\common\common\tools;
use app\zeus\model\CommunityModel;
use app\zeus\model\DwellingModel;
use app\zeus\model\TokenModel;
use app\zeus\model\UserModel;
use think\Controller;

// 用户管理
class User extends Basec {
    // 添加用户界面
    public function add() {
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

    // 添加用户操作，数据返回
    public function addAction() {
        session_start();
        if (!$_SESSION) {
            $result = array('code' => 20022, 'message' => 'session token expired');
            return json_encode($result);
        }
        trace('user/addAction', 'info');

        $result = array('code' => 10017, 'message' => 'invalid paramters');

        // check paramters
        if (input('?mobile') && input('?password') && input('?nickname') && input('?role')) {
            $userinfo = array('username' => input('username'), 'mobile' => input('mobile'), 'password' => input('password'), 'alias' => input('nickname'), 'role' => input('role'));
            $user = new UserModel();
            $result = $user->addUser($_SESSION['zeus']['session_token'], $userinfo);
            $result = json_decode($result, true);
        }

        return json_encode($result);
    }

    // 删除用户，界面返回
    public function delete() {
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

    // TODO 删除用户，数据返回
    public function deleteAction() {
        session_start();
        if (!$_SESSION) {
            $result = array('code' => 20022, 'message' => 'session token expired');
            return json_encode($result);
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

    // 修改信息界面
    public function update() {
        // 用info界面，内容获取用户详情
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        trace('user/add', 'info');

        if (!input('?user_id')) {
            return $this->error('获取用户数据失败', '/user/index');
        }

        // 查询用户数据
        $user = new UserModel();
        $result = $user->getUserDetail($_SESSION['zeus']['session_token'], input('user_id'));
        $result_json = json_decode($result, true);
        if (!$result_json) {
            return $this->error('无法与服务器建立连接，请检查网络!');
        }

        if ($result_json['Rescode'] != 10000) {
            return $this->error('获取用户信息失败, 错误代码' . $result_json['Rescode']);
        }
        // 将用户数据注册到页面变量中
        $this->assign('userinfo', $result_json['Data']);

        $this->assign('step', 'update');
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/info');
    }

    // 更新用户信息, 数据返回
    public function updateAction() {
        session_start();
        if (!$_SESSION) {
            $result = array('code' => 20022, 'message' => 'session token expired');
            return json_encode($result);
        }
        trace('user/updateAction', 'info');

        $result = array('code' => 10017, 'message' => 'invalid paramters');

        if (input('?user_id') && input('?username') && input('?mobile') && input('?nickname') && input('?role')) {
            $userinfo = array('user_id' => input('user_id'), 'username' => input('username'), 'mobile' => input('mobile'), 'alias' => input('nickname'), 'role' => input('role'));
            if (input('?password')) {
                $userinfo['password'] = input('password');
            }
            // echo json_encode($userinfo);
            $user = new UserModel();
            $result = $user->updateUser($_SESSION['zeus']['session_token'], $userinfo);
            trace($result, 'info');
            $result = json_decode($result, true);
        }

        return json_encode($result);
    }

    // 查找用户操作，界面返回
    public function find() {
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
        $page = Tools::makePageUrl('data=' . $_GET['data'], $total, $page_index, $page_size);
        trace("page: " . $page, 'info');

        if (!$user_list) {
            return $this->error('未搜索到相关数据。', 'user/index');
        }
        $this->assign('username', $_SESSION['zeus']['user_name']);
        $this->assign('list', $user_list);
        $this->assign('page', $page);
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/index');
    }

    // 详情页
    public function detail() {
        // 用info界面，内容是预填充的，并且有部分内容不可修改，需要lock住
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (!isset($_GET['user_id'])) {
            return $this->error('请求参数不完整，无法完成查询', 'user/index');
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

        $dwelling = new DwellingModel();
        $result = $dwelling->getUsersDwellings($_SESSION['zeus']['session_token'], $_GET['user_id']);
        $result_json = json_decode($result, true);
        if ($result_json['Rescode'] == 10000) {
            $data_json = $result_json['Data'];
            // echo json_encode($data_json);
            // echo gettype($data_json['dwellings']);

            $this->assign('dwellings', $data_json['dwellings']);
        } else {
            return $this->error('获取用户住房信息失败', 'user/index');
        }

        $this->view->engine->layout('user/layout');
        $this->assign('user_id', $_GET['user_id']);
        return $this->fetch('user/detail');
    }

    // 用户列表
    public function index() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (input('?page')) {
            $page_index = input('page');
        } else {
            $page_index = 1; // 默认显示第一页
        }
        echo "page:" . $page_index;
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
        trace("page: " . $page, 'info');

        if (!$user_list) {
            return $this->error('用户列表为空，请检查服务器数据。');
        }
        $this->assign('username', $_SESSION['zeus']['user_name']);
        $this->assign('list', $user_list);
        $this->assign('page', $page);
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/index');
    }

    // 添加用户关联住房, 返回 成功 | 失败 界面
    public function addDwelling() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (!input('?user_id') || !input('?dwelling_id')) {
            return $this->error('请求参数不完整，无法添加住房', redirect()->restore());
        }
        $dwelling = new DwellingModel();
        $result = $dwelling->addOccupant($_SESSION['zeus']['session_token'], input('dwelling_id'), input('user_id'));
        $result_json = json_decode($result, true);
        if ($result_json['Rescode'] == 10000) {
            return $this->success('住房关联成功', redirect()->restore());
        } else {
            return $this->error('住房关联失败，代码：' . $result_json['Rescode']);
        }
    }

    // 删除用户关联住房，返回 成功 | 失败 界面
    public function deleteDwelling() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (!input('?user_id') || !input('?dwelling_id')) {
            return $this->error('请求参数不完整，无法删除住房', redirect()->restore());
        }
        $dwelling = new DwellingModel();
        $result = $dwelling->deleteOccupant($_SESSION['zeus']['session_token'], input('dwelling_id'), input('user_id'));
        $result_json = json_decode($result, true);
        if ($result_json['Rescode'] == 10000) {
            return $this->success('住房删除成功', redirect()->restore());
        } else {
            return $this->error('住房删除失败，代码：' . $result_json['Rescode']);
        }
    }

    // 修改用户住房权限，返回 成功 | 失败界面
    public function changeDwellingRole() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (!input('?user_id') || !input('?dwelling_id') || !input('?role')) {
            return $this->error('请求参数不完整，无法修改权限', redirect()->restore());
        }
        $dwelling = new DwellingModel();
        $result = $dwelling->updateOccupant($_SESSION['zeus']['session_token'], input('dwelling_id'), input('user_id'), input('role'));
        $result_json = json_decode($result, true);
        if ($result_json['Rescode'] == 10000) {
            return $this->success('修改权限成功', redirect()->restore());
        } else {
            return $this->error('修改权限失败，代码：' . $result_json['Rescode']);
        }
    }

    // 登录记录
    public function record() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        if (input('?page')) {
            $page_index = input('page');
        } else {
            $page_index = 1; // 默认显示第一页
        }

        $page_size = config('paginate')['list_rows'];
        trace('user/record', 'info');
        trace('page_size is: ' . $page_size, 'info');
        trace('page is: ' . $page_index, 'info');

        // 1. API获取登录记录第一页数据，并将数据放至缓存
        // 获取列表并分页显示
        $token = new TokenModel();
        $result = $token->getRecord($_SESSION['zeus']['session_token'], $page_index, $page_size);
        $result_json = json_decode($result, true);

        // 2. 跳转到对应的view
        if (!$result_json) {
            return $this->error('无法与服务器建立连接，请检查网络!');
        }

        if ($result_json['Rescode'] != 10000) {
            return $this->error('获取用户列表失败, 错误代码' . $result_json['Rescode']);
        }

        // echo json_encode($result_json);

        $user_list = $result_json['Data']['signin'];
        $total = $result_json['Data']['total'];

        // 获取分页显示
        $page = Tools::makePage($total, $page_index, $page_size);
        trace("page: " . $page, 'info');

        if (!$user_list) {
            return $this->error('登录记录为空，请检查错误信息。');
        }

        for ($i = 0; $i < count($user_list); $i++) {
            $user_list[$i]['date'] = date('Y-m-d H:i:s', $user_list[$i]['date']);
        }

        $this->assign('list', $user_list);
        $this->assign('page', $page);
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/record');
    }

    // TODO 查询用户登录记录
    public function userRecord() {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }

        if (input('?page')) {
            $page_index = input('page');
        } else {
            $page_index = 1; // 默认显示第一页
        }

        if (!input('?codition')) {
            return $this->error('参数不足，无法查询用户登录记录', '/');
        }
        $page_size = config('paginate')['list_rows'];

        // 1. 查询用户
        $user = new UserModel();
        $result = $user->findStandardUsers($_SESSION['zeus']['session_token'], input('codition'), $page_index, $page_size);
        $result_json = json_decode($result, true);
        $user_list = [];
        if ($result_json['Rescode'] == 10000) {
            $user_list = $result_json['Data']['user_data'];
            // echo json_encode($user_list);
        } else {
            return $this->error('参数错误，错误代码' . $result_json['Rescode'], redirect()->restore());
        }

        // 2. 查询所有用户ID的登录记录
        $record_list = [];
        for ($i = 0; $i < sizeof($user_list); $i++) {
            $token = new TokenModel();
            $result = $token->getUserRecord($_SESSION['zeus']['session_token'], $user_list[$i]['id'], $page_index, $page_size);
            echo $result;
            // $record_list = array_merge($record_list,$result_json)
        }

        $user_list = [];
        $total = 0;

        // 获取分页显示
        $page = Tools::makePage($total, $page_index, $page_size);
        trace("page: " . $page, 'info');

        // 3. 展示出来
        $this->assign('list', $user_list);
        $this->assign('page', $page);
        $this->view->engine->layout('user/layout');
        return $this->fetch('user/record');
    }
}
