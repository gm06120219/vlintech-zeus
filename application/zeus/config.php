<?php
// 超级管理员配置文件

// 解析环境配置文件
// 参数中第一个路径是以/public为相对路径，不可设置绝对路径
// 具体配置文件在同级目录下，绝对路径是：/application/zeus/env.ini
try {
  $config_arr = parse_ini_file("../application/zeus/env.ini",true);
} catch (Exception $e) {
  echo "<script>alert('Environment config file miss. please check program package.')</script>";
  exit();
}

// 动态识别环境
$hostname_file = '/etc/hostname';
// 1. 获取本地hostname文件内容
if (file_exists($hostname_file)) {
  // 读取hostname
  $hostname_read = file_get_contents($hostname_file);
  $hostname = str_replace(array(" ","　","\t","\n","\r"), '', $hostname_read);
  if (array_key_exists($hostname, $config_arr)) {
    $server = $config_arr[$hostname]['choice_server'];
  }
}

// 2. 没获取到就用配置文件里的配置
if (!isset($server)){
  $server = $config_arr['environment']['choice_server'];
}

return [
  // +----------------------------------------------------------------------
  // | 固定参数
  // +----------------------------------------------------------------------
  'VERSION' => $config_arr['environment']['version'],
  'PUBLIC_DIR' => '/public/static',
  // +----------------------------------------------------------------------
  // | 环境变量
  // +----------------------------------------------------------------------
  'domain_url' => $config_arr[$server]['domain_url'],
  'api_url' => $config_arr[$server]['api_url'],
  'admin_api_url' => $config_arr[$server]['admin_api_url'],
  'oss_bucket_name' => $config_arr[$server]['oss_bucket_name'],
];
