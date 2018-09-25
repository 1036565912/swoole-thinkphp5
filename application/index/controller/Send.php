<?php
namespace  app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;
use think\Config;
class Send{

    /**
     * @param $phone_num 手机号码
     * @return json
     * @author chenlin
     * @date 2018/9/19
     */
    public function index(){
        $phone_num = request()->get('phone_num',0,'intval');
        //echo $phone_num;
        if(empty($phone_num)){
            return Util::jsonError(config('dict')['status']['error'],'手机号码为空');
        }

        //生成一个随机数
        $code = mt_rand(1000,9999);

        try{
            $result = Sms::send($phone_num,$code);
        }catch (\Exception $e){
            return Util::jsonError(config('dict.status.error'),'阿里大鱼内部错误!');
        }
        if($result->Code === "OK"){

            //存储在redis
            //以后做成redis连接池
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'),config('redis.port'));
            $redis->set(config('redis.prefix').$phone_num,$code,config('redis.lifeTime'));
            return Util::jsonSuccess(config('dict.status.success'),'发送成功!');
        }else{
            return Util::jsonError(config('dict.status.error'),$result->Message);
        }

    }
}