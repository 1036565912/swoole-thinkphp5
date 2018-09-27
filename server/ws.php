<?php
class ws{
    private $_host;
    private $_port;
    protected  $http;
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

        $this->ws->on('open',[$this,'open']);
        $this->ws->on('message',[$this,'message']);
        $this->ws->on('WorkerStart',[$this,'workStart']);
        $this->ws->on('request',[$this,'request']);
        $this->ws->on('task',[$this,'task']);
        $this->ws->on('finish',[$this,'finish']);
        $this->ws->start();
    }


    public function workStart($serv,$worker_id){
        define('APP_PATH', __DIR__ . '/../application/');
        require __DIR__ . '/../thinkphp/start.php';  //只是加载框架的核心文件
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

        ob_start();
        // 执行应用并响应
        Think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }


    public function task($server,$task_id,$worker_id,$data){
        $task = new  app\common\lib\Task();
        //实现抛送不同的异步任务
        $method = $data['method'];
        $result = $task->$method($data['data']['phone_num'],$data['data']['code']);
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
        echo "server: connect success!";
    }

    public  function message($server,$frame){
        //获取唯一标示符
        $fd = $frame->fd;
        $username = $frame->data;
    }
}


$http = new ws('0.0.0.0',80);