<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 18-9-19
 * Time: 下午2:43
 */

/** redis连接资源 */
return [
    'host' => '0.0.0.0',
    'port' => 6379,
    'lifeTime' => 600, //两分钟
    'prefix' => 'sms_',
    'timeOut' => 5,

    //定义一个资源连接名称
    'live_platform_relations' => 'live_platform_relations',
];