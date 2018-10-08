<?php

namespace app\common\model;

use think\Model;

class Chart extends Model{
    //
    protected $pk = "id";


    //设置常量 0 暂时不用
    const LEGAL_CHART = 1;
    const ILLEGAL_CHART = 2;
}
