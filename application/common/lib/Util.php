<?php
namespace app\common\lib;

class Util{
    /**
     * api JSON失败返回格式
     * @param $status
     * @param $msg
     * @param $data
     * @author chenlin
     * @date 2018//9/19
     */
    public static  function jsonError($status,$msg='',$data=[]){
        $arr = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
        ];
        echo json_encode($arr);
    }

    /**
     * api JSON成功返回格式
     * @param $status
     * @param string $msg
     * @param array $data
     */
    public static function jsonSuccess($status,$msg='',$data=[]){
        $arr = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data
        ];
        echo json_encode($arr);
    }
}