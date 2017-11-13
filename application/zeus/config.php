<?php
// 超级管理员配置文件

// T for testing, S for staging, P for production, V for preview, D for demo, default for T
$environment = "T";

switch ($environment) {
  case 'T':
    $domain_url = "https://gddb.vlings.net";
    $api_url = "https://gddb.vlings.net/api/home";
    $admin_api_url = "https://gddb.vlings.net/api/admin";

    $oss_access_key_id = "LTAIpLXF3A519eCW";
    $oss_access_key_secret = "0NwbXLjIdd3dcvGuJpjaDgbRXLpVr8";
    $oss_role_arn = "acs:ram::1773652787017876:role/ossreadonly";
    $oss_bucket_name ="vlintechtesting";
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

    // +----------------------------------------------------------------------
    // | 阿里云OSS
    // +----------------------------------------------------------------------
  'OSS_AKEY_ID' => $oss_access_key_id,
  'OSS_AKEY_SECRET' => $oss_access_key_secret,
  'OSS_ROLE_ARN' => $oss_role_arn,
  'OSS_BUCKET_NAME' => $oss_bucket_name,
  'OSS_END_POINT' => "oss-cn-hangzhou.aliyuncs.com",
  'OSS_TOKEN_ETIME' => 1000,
];
