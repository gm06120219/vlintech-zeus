<?php
namespace app\zeus\model;

use think\Model;

class Basem extends Model
{
  public function _initialize()
  {
  }

  public function apiGet()
  {
    # code...
  }

  public function apiPost()
  {
    # code...
  }

  public function apiAdminGet($req_path, $get_param)
  {
    $url = config("admin_api_url") . $req_path;
    if ($get_param) {
      $url = $url . "?";
    }

    foreach ($get_param as $key => $value) {
      $url = $url . $key . '=' . $value . '&';
    }

    $options = array(
      'http' => array(
        'method' => 'GET',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'timeout' => 3 // 超时时间（单位:s）
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
  }

  public function apiAdminPost($req_path, $post_param)
  {
    $url = config("admin_api_url") . $req_path;
    $postdata = http_build_query($post_param);
    $options = array(
      'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 3 // 超时时间（单位:s）
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
  }
}
