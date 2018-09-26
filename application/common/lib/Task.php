<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 18-9-26
 * Time: 下午4:34
 */

namespace  app\common\lib;
/** 任务助手类 用来同意分发执行不同的任务 */
use app\common\lib\ali\Sms;

class Task{
    public function smsSend($phone_num,$code){

        try{
            $response = Sms::send($phone_num,$code);
        }catch (\Exception $e){
            //如果发送短信出现问题的话，则进行短信日志错误调试
            echo   $e->getMessage();
        }
        if($response->Code == 'OK'){
            return true;
        }else{
            return false;
        }
    }
}