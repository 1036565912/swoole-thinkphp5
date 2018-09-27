<?php
namespace  app\index\controller;

use app\common\lib\Predis;
use app\common\lib\Util;

class Login{
    /**
     *  登录验证控制器方法
     */
    public function login(){
        $phone_number = request()->post('phone_num');
        $code = request()->post('code');

        try{
            $redis = Predis::getInstance();
        }catch(\Exception $e){
            return Util::jsonError(500,$e->getMessage());
        }

        //验证验证码的时候 只能同步 不能使用异步客户端和携程 这样逻辑就会出现错误
        $key = config('redis.prefix').$phone_number;
        $last_code  = $redis->get($key);
        if($code == $last_code){
            $data = [
                'status' => 'login',
                'login_time' => date('Y-m-d H:i:s',time()),
                'username' => '狼牙山',
                'cookie_life_time' =>  365, //天
                'path'         => '/',  //设置cookie的路径
            ];
            return Util::jsonSuccess(200,'登录成功!',$data);
        }else{
            return Util::jsonError(500,'验证码已经失效!');
        }

    }
}
