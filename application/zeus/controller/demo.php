<?php
namespace app\zeus\controller;

use think\Log;
use think\Session;
use think\Controller;
use app\common\common\tools;
use app\zeus\model\oss;
use org\util\ArrayList;
use aliyun\oss_sts\Sts;

class Demo extends Basec
{
    public function index()
    {
        $this->view->engine->layout('demo/layout');
        $oss = new Oss();
        $result = $oss->getToken();
        // var_dump($result);
        $this->assign('oss_key_id', $result["AccessKeyId"]);
        $this->assign('oss_key_secret', $result["AccessKeySecret"]);
        $this->assign('oss_token', $result["SecurityToken"]);
        $this->assign('oss_expiration', $result["Expiration"]);
        $this->assign('hostname', $_SERVER['SERVER_NAME']);
        return $this->fetch('demo/index');
    }

    public function token()
    {
        $oss = new Oss();
        $result = $oss->getToken();
        $result_json = json_decode($result, true);
        return $result_json;
    }

    public function tlist()
    {
        $list       = ['thinkphp', 'thinkphp', 'onethink', 'topthink'];
        $arrayList  = new ArrayList($list);
        $arrayList->add('kancloud');
        $arrayList->unique();
        dump($arrayList->toArray());
        echo $arrayList->toJson();
    }

    public function tsts()
    {
        $sts = new Sts("config.json");
        // echo $sts->getSession();
    }
}
