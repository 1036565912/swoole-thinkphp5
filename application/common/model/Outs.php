<?php

namespace app\common\model;

use think\Model;

class Outs extends Model
{
    // 设置主键  默认的是自动识别
    protected  $pk = 'id';


    //设置状态常量
    const NOT_USE_STATUS = 1;
    const USED_STATUS = 2;

}
