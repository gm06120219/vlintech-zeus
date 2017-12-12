<?php
namespace app\zeus\model;

use think\Model;

class UserModel extends Basem {
    public function getUsers($token, $page, $page_size) {
        $api_path = "/users/get_users";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            // 'condition'=> $condition,
            // 'role'=>$role
            'page' => $page,
            'page_size' => $page_size,
        ));

        return $result;
    }

    // 获取用户的详细信息
    public function getUserDetail($token, $user_id) {
        $api_path = "/users/get_user_brief";
        $param = array(
            'session_token' => $token,
        );

        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'user_id' => $user_id,
        ));

        return $result;
    }

    // 添加用户
    public function addUser($token, $userinfo) {
        $api_path = "/users/add_user";
        if (!isset($userinfo['mobile']) ||
            !isset($userinfo['password']) ||
            !isset($userinfo['alias']) ||
            !isset($userinfo['role'])) {
            $result = array('code' => 10017, 'msg' => 'invalid paramters model' . json_encode($userinfo));
            return json_encode($result);
        }

        $userinfo['session_token'] = $token;
        $result = $this->apiAdminPost($api_path, $userinfo);

        return $result;
    }

    // 查询用户 - 不带类型
    public function findStandardUsers($token, $condition, $page, $page_size) {
        $api_path = "/users/get_users";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'condition' => $condition,
            'role' => 0,
            'page' => $page,
            'page_size' => $page_size,
        ));
        return $result;
    }

    // 更新用户信息
    public function updateUser($token, $userinfo) {
        $api_path = "/users/update_user";
        if (empty($userinfo['user_id'])) {
            $result = array('code' => 10017, 'msg' => 'invalid paramters');
            return json_encode($result);
        }

        $userinfo['session_token'] = $token;
        // trace('user/updateUser', json_encode($userinfo));
        $result = $this->apiAdminPost($api_path, $userinfo);

        return $result;
    }

    // 删除用户（API层就只是禁用该用户）
    public function deleteUser($token, $user_id) {
        $api_path = "/users/update_user";
        $result = $this->apiAdminPost($api_path, array(
            'session_token' => $token,
            'user_id' => $user_id,
            'enabled' => 0,
        ));
        return $result;
    }
}
