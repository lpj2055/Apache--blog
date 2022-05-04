<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//PHP获取磁盘大小
function get_disk_total(int $total)
{
    $config = [
        '3' => 'GB',
        '2' => 'MB',
        '1' => 'KB'
    ];
    foreach($config as $key => $value){
        if($total > pow(1024, $key)){
            return round($total / pow(1024,$key)).$value;
        }
        return $total . 'B';
    }
}