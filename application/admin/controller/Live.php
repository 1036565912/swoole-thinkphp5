<?php
namespace  app\admin\controller;
use app\common\model\Outs;
use app\common\lib\Util;
use app\common\lib\Predis;
class Live {

    //解说员后台推送数据方法
    public function push(){
        $type = request()->post('type',0);
        $team_id = request()->post('team_id',0);
        $content = request()->post('content','');
        $img = request()->post('img','');
        $time = request()->post('time','');
        if(empty($type) || empty($content)){
            return Util::jsonError(config('dict.status.error'),'输入的信息有误！');
        }
        //首先把要推送的数据存放在数据库 以备后面的使用
        $Outs = new Outs();
        $Outs->game_id = 1;
        $Outs->team_id = $team_id;
        $Outs->content = $content;
        $Outs->image = $img;
        $Outs->type = $type;
        $Outs->status = Outs::NOT_USE_STATUS;
        $Outs->create_time = time();
        $Outs->publish_time = $time;
        if(!$Outs->save()){
            return Util::jsonError(config('dict.status.error'),'推送失败!');
        }
        //然后进行数据的推送  push方法推送成功返回true  推送失败 返回false
        //但是在实时场景中 需要获取所有的连接 循环推送
        $team = [
            1 =>  [
                'name' => '马刺',
                'logo' => '/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/imgs/team4.png'
            ]
        ];
        //按理来说  后台会搭建在公司内网中 并且在进行数据推送的时候 可以使用token验证 内容进行加密
        //这里连接客户端比较多的话  需要消耗很长的时间  这里可以使用task任务进行异步推送 客户端直接返回信息。
        $data = [
            'title' => empty($team[$team_id]['name']) ? '评论员' : $team[$team_id]['name'],
            'logo'  =>  empty($team[$team_id]['logo']) ? '' : $team[$team_id]['logo'],
            'content' => $content,
            'img' => $img,
            'time' => $time,
            'type' => $type,
        ];
        $taskData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $_POST['http_server']->task($taskData);
        return Util::jsonSuccess(config('dict.status.success'),'推送成功!');


    }
}