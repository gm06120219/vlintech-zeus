<?php
namespace app\zeus\model;

use think\Model;

class CommunityModel extends Basem
{
  public function getCommunities($token, $page, $page_size)
  {
    $api_path = "/communities/get_communities";
    $result = $this->apiAdminGet($api_path, array(
      'session_token' => $token,
      // 'condition'=> $condition,
      // 'role'=>$role
      'page' => $page,
      'page_size' => $page_size
    ));

    return $result;
  }

  public function detail($token, $community_id)
  {
    $api_path = "/communities/get_communities";
    $result = $this->apiAdminGet($api_path, array(
      'session_token' => $token,
      'community_id' => $community_id
    ));

    return $result;
  }
}
