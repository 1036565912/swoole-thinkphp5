<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 18-9-19
 * Time: 下午2:08
 */

/** 用户自定义字典表 */
return [
    //返回状态代码定义
    'status' => [
        'error' => 500,
        'success' => 200,
    ],
    'upload_path' => dirname(APP_PATH).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR,
    'APP_URL' => 'http://swoole.com',
];