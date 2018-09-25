<?php
$http = new swoole_http_server('0.0.0.0',80);
$http->set([
	'enable_static_handler' => true,
	'document_root' => '/home/chen/www/thinkphp5/public/static/',
	'worker_num' => 4,
]);

$http->on('WorkerStart',function($serv,$worker_id){
	define('APP_PATH', __DIR__ . '/../application/');
	require __DIR__ . '/../thinkphp/base.php';
});
$http->on('request',function($request,$response) use ($http){
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

    $_POST = [];
	if(isset($request->post)){
		 foreach($request->post as $k => $v){
	 		$_POST[$k] = $v;
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
});

$http->start();