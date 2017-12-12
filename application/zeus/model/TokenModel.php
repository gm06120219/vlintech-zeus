<?php
namespace app\zeus\model;

use think\Model;

class TokenModel extends Basem {
    public function getToken($username = '', $password = '') {
        $api_path = "/sessions/get_session";
        $result = $this->apiAdminPost($api_path, array(
            'username' => $username,
            'password' => $password,
            'developer_id' => '1003',
        ));

        return $result;
    }

    public function getRecord($token, $page, $page_size) {
        $api_path = "/sessions/get_signininfo";

        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

    public function getUserRecord($token, $user_id, $page, $page_size) {
        $api_path = "/sessions/get_signininfo";

        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'user_id' => $user_id,
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }
}
