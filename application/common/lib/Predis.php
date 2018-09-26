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
}