<?php
namespace  app\admin\controller;

class Image{
    public function index(){
        var_dump(request()->file('file'));
    }
}