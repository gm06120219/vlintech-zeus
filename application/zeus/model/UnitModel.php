<?php
namespace app\zeus\model;

use think\Model;

class UnitModel extends Basem
{
  public function getUnits($token, $community_id)
  {
    $api_path = "/units/get_buildings";
    $result = $this->apiAdminGet($api_path, array(
      'session_token' => $token,
      'community_id' => $community_id
    ));

    return $result;
  }
}
