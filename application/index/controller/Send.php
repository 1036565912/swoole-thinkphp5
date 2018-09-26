<?php
namespace  app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;
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
        //echo $code;
        $data = [
            'method' => 'smsSend',
            'data'  => [
                'phone_num' => $phone_num,
                'code' => $code,
            ]
        ];

        //由于这里是对接第三方的接口，然后第三方的返回结果都是不可信，为了不影响用户的体验，这里我们需要投递一个异步任务去执行，主线程继续执行。
        $_POST['http_server']->task($data);
        //存储在redis
        //以后做成redis连接池
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect(config('redis.host'),config('redis.port'));
        $redis->setex(config('redis.prefix').$phone_num,config('redis.lifeTime'),$code);
        return Util::jsonSuccess(config('dict.status.success'),'发送成功!');

    }
}