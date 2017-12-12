<?php
namespace app\zeus\model;

use think\Model;

class FloorplanModel extends Basem
{
  // 获取户型列表
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

  // 添加户型
  public function add($token, $com_id, $plan_name)
  {
    $api_path = "/floorplans/add_floorplan";
    $result = $this->apiAdminPost($api_path, array(
      'session_token' => $token,
      'name' => $plan_name,
      'community' => $com_id,
      // 'deployment_configuration_url' => '',
      // 'self_update_configuration_url' => '',
      // 'control_configuration_url' => '',
    ));
    return $result;
  }

  // 单个户型详情
  public function detail($token, $floorplan_id)
  {
    $api_path = "/floorplans/get_floorplans";
    $result = $this->apiAdminGet($api_path, array(
      'session_token' => $token,
      'floorplan_id' => $floorplan_id
    ));

    return $result;
  }
}
