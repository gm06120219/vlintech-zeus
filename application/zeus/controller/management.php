<?php
namespace app\zeus\controller;

use app\common\common\tools;
use app\zeus\model\ManagementModel;
use think\Controller;

// 物业管理
class Management extends Basec {

    // 社区列表
    public function index() {
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
        trace('management/index', 'info');
        trace('page_size is: ' . $page_size, 'info');
        trace('page is: ' . $page_index, 'info');

        // 1. API获取用户列表第一页数据，并将数据放至缓存
        // 获取列表并分页显示
        $man = new ManagementModel();
        $result = $man->getManagements($_SESSION['zeus']['session_token'], $page_index, $page_size);
        $result_json = json_decode($result, true);

        // 2. 跳转到对应的view
        if (!$result_json) {
            return $this->error('无法与服务器建立连接，请检查网络!');
        }

        if ($result_json['Rescode'] != 10000) {
            return $this->error('获取物业列表失败, 错误代码' . $result_json['Rescode']);
        }

        $developer_list = $result_json['Data']['management'];
        $total = $result_json['Data']['total'];

        // 获取分页显示
        $page = Tools::makePage($total, $page_index, $page_size);
        trace("page: " . $page, 'info');

        if (!$developer_list) {
            return $this->error('物业列表为空，请检查服务器数据。');
        }

        $this->assign('username', $_SESSION['zeus']['user_name']);
        $this->assign('list', $developer_list);
        $this->assign('page', $page);
        $this->view->engine->layout('management/layout');
        return $this->fetch('management/index');
    }
}
