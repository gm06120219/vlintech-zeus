<?php
namespace app\zeus\controller;

use app\common\common\tools;
use app\zeus\model\NewsModel;
use think\Controller;

// 单元管理
class News extends Basec {

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
        trace('user/index', 'info');
        trace('page_size is: ' . $page_size, 'info');
        trace('page is: ' . $page_index, 'info');

        // 1. API获取用户列表第一页数据，并将数据放至缓存
        // 获取列表并分页显示
        $news = new NewsModel();
        $result = $news->getNews($_SESSION['zeus']['session_token'], $page_index, $page_size);
        $result_json = json_decode($result, true);

        // 2. 跳转到对应的view
        if (!$result_json) {
            return $this->error('无法与服务器建立连接，请检查网络!');
        }

        if ($result_json['Rescode'] != 10000) {
            return $this->error('获取开发商列表失败, 错误代码' . $result_json['Rescode']);
        }

        // echo json_encode($result_json);
        $news_list = $result_json['Data']['messages'];
        $total = $result_json['Data']['count'];

        for ($i = 0; $i < count($news_list); $i++) {
            $news_list[$i]['community_list'] = "";
            $news_list[$i]['community_list_title'] = "";

            for ($j = 0; $j < count($news_list[$i]['communities']); $j++) {
                if ($j < 3) {
                    $news_list[$i]['community_list'] .= " " . $news_list[$i]['communities'][$j]['community_name'];
                }
                $news_list[$i]['community_list_title'] .= $news_list[$i]['communities'][$j]['community_name'] . "\n";
            }
            if (count($news_list[$i]['communities']) > 3) {
                $news_list[$i]['community_list'] .= ' ...';
            }
            $news_list[$i]['date'] = date('Y-m-d H:i:s', $news_list[$i]['timestamp']);
        }

        // 获取分页显示
        $page = Tools::makePage($total, $page_index, $page_size);
        trace("page: " . $page, 'info');

        if (!$news_list) {
            return $this->error('开发商列表为空，请检查服务器数据。');
        }

        $this->assign('username', $_SESSION['zeus']['user_name']);
        $this->assign('list', $news_list);
        $this->assign('page', $page);
        $this->view->engine->layout('news/layout');
        return $this->fetch('news/index');
    }
}
