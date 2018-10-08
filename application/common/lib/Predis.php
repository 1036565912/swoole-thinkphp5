<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 18-9-25
 * Time: 上午10:13
 */

namespace app\common\lib;

//设置redis连接资源 单例模式
class Predis{
    private static $instance = null;
    private $host;
    private $port;
    public  $redis;

    public function __construct(){
        $this->host = config('redis.host');
        $this->port = config('redis.port');
        $this->redis = new \Redis();
        $result = $this->redis->connect($this->host,$this->port,config('redis.timeOut'));
        if($result ===  false){
            throw new  \Exception('redis connect error');
        }
    }

    /**
     * 单例模式 来实现同一个生命周期之内使用同一个redis连接资源
     * @return Predis|null
     */
    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($sms_phone){
        $result = $this->redis->get($sms_phone);
        return $result;
    }

//    /**
//     * 添加数据到集合中
//     * @param $key
//     * @param $value
//     * @return int
//     */
//    public function sAdd($key,$value){
//        $result = $this->redis->sAdd($key,$value);
//        return $result;
//
//    }
//
//
//    public function sRem($key,$value){
//        $result = $this->redis->sRem($key,$value);
//        return $result;
//
//    }
//
//    public function sMembers($key){
//        $result = $this->redis->sMembers($key);
//        return $result;
//    }

    /**
     * 定义魔术方法来实现多种方法封装
     * @param $name
     * @param $argument
     */
    public function __call($name,$argument){
         if(count($argument) == 1){
             //一个参数
             return $this->redis->$name($argument[0]);
         }
         if(count($argument) == 2){
             //两个参数
             return $this->redis->$name($argument[0],$argument[1]);
         }
    }
}