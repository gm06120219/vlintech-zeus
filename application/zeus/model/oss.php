<?php
namespace app\zeus\model;

use think\Model;

class Oss extends Basem
{
    private $_id;
    private $_secret;
    private $_token;

    public function __construct()
    {
        try {
            $this->getToken();
        } catch (Exception $e) {
            $this->$_id = NULL;
            $this->$_secret = NULL;
            $this->$_token = NULL;
        }
    }

    # 通过/public/static/plugins/sts-server/sts.php获取token
    public function getToken()
    {
        $url = "http://127.0.0.1/public/static/plugins/oss-sts-server/sts.php";
        $result = file_get_contents($url);
        $result_json = json_decode($result, true);

        if (!$result && !result_json && !$result_json['status']) {
            if ($result_json['AccessKeyId'] && $result_json['AccessKeySecret'] && $result_json['SecurityToken']) {
                $this->$_id = $result_json['AccessKeyId'];
                $this->$_secret = $result_json['AccessKeySecret'];
                $this->$_token = $result_json['SecurityToken'];
            } else {
                throw new Exception("oss sts get token failure");
            }
        }
        return $result_json;
    }

    # 前端调用上传文件
    public function uploadFile()
    {
    }

    # 前端调用查看文件列表
    public function listFile()
    {
        if ($this->$_id == NULL) {
            echo json_decode(array('code' => '10017', 'msg' => 'no sts token'), true);
        } else {

        }
    }

    # 前端调用下载文件
    # TODO 暂时用不到
    public function downloadFile()
    {
    }
}
