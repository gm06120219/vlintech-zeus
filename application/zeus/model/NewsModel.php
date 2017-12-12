<?php
namespace app\zeus\model;

use think\Model;

class NewsModel extends Basem {
    public function getNews($token, $page, $page_size) {
        $api_path = "/information/get_community_notifications";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

    // TODO 详细信息
    public function detail($token, $community_id, $news_id) {

    }

    // 模糊匹配
    public function findNews($token, $condition, $page, $page_size) {
        $api_path = "/information/get_community_notifications";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'name' => $condition,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

}
