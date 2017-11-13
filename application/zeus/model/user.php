<?php
namespace app\zeus\model;

use think\Model;

class User extends Basem
{
    public function getUsers($token, $page, $page_size)
    // public function getUsers($token, $condition, $role)
    {
        $api_path = "/users/get_users";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            // 'condition'=> $condition,
            // 'role'=>$role
            'page' => $page,
            'page_size' => $page_size
        ));

        return $result;
    }

    // 获取用户的详细信息
    public function getUserDetail($token, $user_id, $username, $mobile)
    {
        $api_path = "/users/get_user_brief";
        $param = array(
            'session_token' => $token,
        );
        if (!isset($user_id)) {
            array_push($param, "user_id", $user_id);
        }
        if (!isset($username)) {
            array_push($param, "username", $username);
        }
        if (!isset($mobile)) {
            array_push($param, "mobile", $mobile);
        }
        $result = $this->apiAdminGet($api_path, $param);

        return $result;
    }
}
