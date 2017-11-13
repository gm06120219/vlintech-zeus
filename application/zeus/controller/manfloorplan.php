<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\zeus\model\floorplan;
use app\common\common\tools;

// 户型管理
class Manfloorplan extends Basec
{
    // TODO 添加
    public function add()
    {
        session_start();
        if (!$_SESSION) {
            return $this->error('登录超时，请重新登录', '/');
        }
        trace('Manfloorplan/add', 'info');

        $this->assign('step', 'add');

        $this->view->engine->layout('floorplan/layout');
        return $this->fetch('floorplan/detail');
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
        $floorplan = new Floorplan();
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

}
