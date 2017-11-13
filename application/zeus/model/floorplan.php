<?php
namespace app\zeus\model;

use think\Model;

class Floorplan extends Basem
{
    public function getFloorplans($token, $page, $page_size)
    {
        $api_path = "/floorplans/get_floorplans";
        $result = $this->apiAdminGet($api_path, array(
            'session_token' => $token,
            // 'condition'=> $condition,
            // 'role'=>$role
            'page' => $page,
            'page_size' => $page_size
        ));

        return $result;
    }
}
