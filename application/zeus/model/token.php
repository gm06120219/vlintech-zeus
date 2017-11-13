<?php
namespace app\zeus\model;

use think\Model;

class Token extends Basem
{
    public function getToken($username='', $password='')
    {
        $api_path = "/sessions/get_session";
        $result = $this->apiAdminPost($api_path, array(
            'username'      => $username,
            'password'      => $password,
            'developer_id'  => '1003'
        ));

        return $result;
    }
}
