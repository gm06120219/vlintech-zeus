<?php
namespace app\zeus\model;

use think\Model;

class DeveloperModel extends Basem {
    public function getDevelopers($token, $page, $page_size) {
        $api_path = "/developers/get_developers";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

    // 模糊匹配
    public function findDeveloper($token, $condition, $page, $page_size) {
        $api_path = "/developers/get_developers";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'name' => $condition,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }
}
