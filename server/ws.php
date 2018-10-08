<?php
use app\common\lib\Predis;
class ws{
    private $_host;
    private $_port;
    protected  $ws;
    //绑定多端口
    const CHART_PORT = 8080;
    public function __construct($host,$port){
        $this->_host = $host;
        $this->_port = $port;
        $this->ws = new swoole_websocket_server($this->_host,$this->_port);
        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => '/home/chen/www/thinkphp5/public/static/',
            'worker_num' => 2,
            'task_worker_num' => 2,
        ]);
        //绑定多个端口
        $this->ws->listen($this->_host,self::CHART_PORT,SWOOLE_SOCK_TCP);
        $this->ws->on('open',[$this,'open']);
        $this->ws->on('message',[$this,'message']);
        $this->ws->on('WorkerStart',[$this,'workStart']);
        $this->ws->on('request',[$this,'request']);
        $this->ws->on('task',[$this,'task']);
        $this->ws->on('finish',[$this,'finish']);
        $this->ws->on('close',[$this,'close']);
        $this->ws->start();
    }

    public function workStart($serv,$worker_id){
        define('APP_PATH', __DIR__ . '/../application/');
        require __DIR__ . '/../thinkphp/start.php';  //只是加载框架的核心文件

        //每次服务重启的时候 需要清空连接信息集合  防止存在不存在的连接资源存在 从而在推送的时候 出现致命错误
        Predis::getInstance()->del(config('redis.live_platform_relations'));
    }

    public function request($request,$response){
        //  //初始化超全局数据
        $_SERVER = [];
        if(isset($request->server)){
            foreach($request->server as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if(isset($request->header)){
            foreach($request->header as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if(isset($request->get)){
            foreach($request->get as $k => $v){
                $_GET[$k] = $v;
            }
        }

        $_FILES = [];
        if(isset($request->files)){
            foreach($request->files as $k => $v){
                $_FILES[$k] = $v;
            }
        }

        $_POST = [];
        if(isset($request->post)){
            foreach($request->post as $k => $v){
                $_POST[$k] = $v;
            }
        }

        $_POST['http_server'] = $this->ws;

        $_COOKIE = [];
        if(isset($request->cookie)){
            foreach($request->cookie as $k => $v){
                $_COOKIE[$k] = $v;
            }
        }

        ob_start();
        // 执行应用并响应
        Think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    /**
     * @param $server  就是初始化的server服务本身
     * @param $task_id
     * @param $worker_id
     * @param $data
     * @return mixed
     */
    public function task($server,$task_id,$worker_id,$data){
        $task = new  app\common\lib\Task();
        //实现抛送不同的异步任务
        $method = $data['method'];
        $result = $task->$method($server,$data['data']);
        return $result;
    }


    /**
     * finish回调函数中的data是task回调的返回值
     * @param $server
     * @param $task_id
     * @param $data
     */
    public function finish($server,$task_id,$data){
        echo "taskId:{$task_id}\n";
        echo "finish-data-success:{$data}";
    }

    public  function open($server,$request){
        //print_r($server);
        //客户端和服务器端建立连接 此时我们可以把连接存放在 redis有序集合中 或者table共享内存中
        Predis::getInstance()->sAdd(config('redis.live_platform_relations'),$request->fd);
        echo "server: connect success!{$request->fd}";
    }

    public  function message($server,$frame){
        //获取唯一标示符
        $fd = $frame->fd;
        $username = $frame->data;
    }

    public function close($server,$fd){
        //当客户端和服务器端断开连接的时候 就从有序集合或者table中去掉 即可
        Predis::getInstance()->sRem(config('redis.live_platform_relations'),$fd);
        echo "{$fd} 已经断开连接!";
    }
}


$http = new ws('0.0.0.0',80);