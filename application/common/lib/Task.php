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
    public function smsSend($server,$data){

        try{
            $response = Sms::send($data['phone_num'],$data['code']);
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


    /** tip: 测试发现 worker进程 常驻变量无法给task进程使用  这里只能使用参数传递方式来进行数据交互*/
    public function pushLive($server,$data){
        $relations = Predis::getInstance()->sMembers(config('redis.live_platform_relations'));
        foreach($relations as $fd){
            $server->push($fd,json_encode($data)); //通过唯一识别号来进行推送
        }
        return true;
    }

    public function chart($server,$data){
        foreach($server->ports[1]->connections as $fd){
            $server->push($fd,json_encode($data));
        }
        return true;
    }
}