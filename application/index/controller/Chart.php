<?php
/**  聊天信息控制器 */
namespace  app\index\controller;
use app\common\lib\Util;
use Cookie;
use Session;
use app\common\model\Chart as charts;
class Chart{
    /**
     *  上传聊天信息方法
     */
    public function index(){
        echo Session('name');
        return ;
        //判断是否登录
        $userName = Cookie('username');
        $login_status = Cookie('login_status');
        $user_id = Cookie('user_id');
        if(empty($userName) || empty($login_status)){
            return Util::jsonError(config('dict.status.error'),'请先登录!');
        }
        $chart_content = request()->post('content','');
        $game_id = request()->post('game_id',0);
        Session('name','thinkphp');
        if(empty($chart_content)){
            return Util::jsonError(config('dict.status.error'),'聊天信息不能为空');
        }
        $data = [
            'username' => $userName,
            'content' => $chart_content,
        ];
        //可以直接写到异步任务中
        $taskData = [
            'method' => 'chart',
            'data' => $data,
        ];
        //使用异步任务来进行消息推送
        $_POST['http_server']->task($taskData);
        //按照原理说 这里需要入库操作 方便以后进行查询  当用户第一次进入页面的时候 我们需要加载最近20条聊天记录信息
        $chart = new charts();
        $chart->game_id = $game_id;
        $chart->user_id = $user_id;
        $chart->content = $chart_content;
        $chart->create_time = time();
        $chart->status = charts::LEGAL_CHART;
        if($chart->save()){
            return Util::jsonSuccess(config('dict.status.success'),'发布成功!');
        }else{
            return Util::jsonSuccess(config('dict.status.success'),'发布失败!');
        }


    }
}
