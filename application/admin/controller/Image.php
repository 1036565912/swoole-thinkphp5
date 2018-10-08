<?php
namespace  app\admin\controller;
use app\common\lib\Util;
class Image{
    public function index(){
        $file = request()->file('file');
        if($info = $file->move(config('dict.upload_path'))){
            return Util::jsonSuccess(config('dict.status.success'),'上传成功!',['img_path' => config('dict.APP_URL').'/'.$info->getSaveName()]);
        }else{
            return  Util::jsonError(config('dict.status.error',$file->getError()));
        }
    }
}