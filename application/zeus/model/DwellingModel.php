<?php
namespace app\zeus\model;

use think\Model;

class DwellingModel extends Basem {
    // 获取单元的所有住房
    public function getUnitDwellings($token, $community_id, $unit_id) {
        $api_path = "/dwellings/get_dwellings";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'community_id' => $community_id,
            'building_id' => $unit_id,
        ));

        return $result;
    }

    // 获取用户的所有住房
    public function getUsersDwellings($token, $user_id) {
        $api_path = "/dwellings/get_user_dwellings";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            'user_id' => $user_id,
        ));

        return $result;
    }

    // 添加住房内的用户
    public function addOccupant($token, $dwelling_id, $user_id) {
        $api_path = "/dwellings/add_occupant";
        $result = $this->apiAdminPost($api_path, array(
            'session_token' => $token,
            'dwelling_id' => $dwelling_id,
            'user_id' => $user_id,
        ));

        return $result;
    }

    // 删除住房内用户
    public function deleteOccupant($token, $dwelling_id, $user_id) {
        $api_path = "/dwellings/update_occupant_role";
        $result = $this->apiAdminPost($api_path, array(
            'session_token' => $token,
            'dwelling_id' => $dwelling_id,
            'user_id' => $user_id,
            'role' => 0,
        ));

        return $result;
    }

    // 修改住房内用户权限
    public function updateOccupant($token, $dwelling_id, $user_id, $role) {
        $api_path = "/dwellings/update_occupant_role";
        $result = $this->apiAdminPost($api_path, array(
            'session_token' => $token,
            'dwelling_id' => $dwelling_id,
            'user_id' => $user_id,
            'role' => $role,
        ));

        return $result;
    }
}
