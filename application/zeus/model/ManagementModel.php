<?php
namespace app\zeus\model;

use think\Model;

class ManagementModel extends Basem {
    public function getManagements($token, $page, $page_size) {
        $api_path = "/propertymanagement/get_managements";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

    // 模糊匹配
    public function findDeveloper($token, $condition, $page, $page_size) {
        $api_path = "/propertymanagement/get_managements";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'name' => $condition,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }
}
