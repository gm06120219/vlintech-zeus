<?php
// 超级管理员配置文件

// T for testing, S for staging, P for production, V for preview, D for demo, default for T
$environment = "T";

switch ($environment) {
  case 'T':
    $domain_url = "https://gddb.vlings.net";
    $api_url = "https://gddb.vlings.net/api/home";
    $admin_api_url = "https://gddb.vlings.net/api/admin";
    break;

  default:
    $domain_url = "https://gddb.vlings.net";
    $api_url = "https://gddb.vlings.net/api/home";
    $admin_api_url = "https://gddb.vlings.net/api/admin";
    break;
}

return [
  // +----------------------------------------------------------------------
  // | 自定义变量
  // +----------------------------------------------------------------------
  'VERSION' => '3.0.0',
  'PUBLIC_DIR' => '/public/static',
  'domain_url' => $domain_url,
  'api_url' => $api_url,
  'admin_api_url' => $admin_api_url,
];
