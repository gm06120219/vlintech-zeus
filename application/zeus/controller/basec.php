<?php
namespace app\zeus\controller;

use think\Controller;

class Basec extends Controller {

    public function _initialize(){
      // echo "initialize";
    }

    /**
     * 自定义初始化函数
     */
    public function init(){
      // echo "init";
    }

    /**
     * Ajax正确返回，自动添加debug数据
     * @param $msg
     * @param array $data
     * @param int $code
     */

    public function ajaxSuccess( $msg, $code = 1, $data = array() ){
        $returnData = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
        if( !empty($this->debug) ){
            $returnData['debug'] = $this->debug;
        }
        $this->ajaxReturn($returnData, 'json');
    }

    /**
     * Ajax错误返回，自动添加debug数据
     * @param $msg
     * @param array $data
     * @param int $code
     */

    public function ajaxError( $msg, $code = 0, $data = array() ){
        $returnData = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
        if( !empty($this->debug) ){
            $returnData['debug'] = $this->debug;
        }
        $this->ajaxReturn($returnData, 'json');
    }

}
