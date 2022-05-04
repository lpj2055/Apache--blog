<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型 支持 php think 支持扩展
    'type'         => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'    => 1,
    // 模板路径
    'view_path'    => '',
    // 模板后缀
    'view_suffix'  => 'html',
    // 模板文件名分隔符
    'view_depr'    => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'    => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'      => '}',
    // 标签库标签开始标记
    'taglib_begin' => '{',
    // 标签库标签结束标记
    'taglib_end'   => '}',

    // 模板输出替换
    'tpl_replace_string'     =>  [
        '__STATIC__'         => '/public/static',
        '__JS__'             => '/public/static/js',
        '__CSS__'            => '/public/static/css',
        '__IMAGES__'         => '/public/static/images',
        '__XADM_JS__'        => '/public/static/x-admin/js',
        '__XADM_CSS__'       => '/public/static/x-admin/css',
        '__XADM_IMAGES__'    => '/public/static/x-admin/images',
        '__XADM_LIB__'       => '/public/static/x-admin/lib',
        '__TEMPLATE1_JS__'        => '/public/static/template1/js',
        '__TEMPLATE1_CSS__'       => '/public/static/template1/css',
        '__TEMPLATE1_IMAGES__'    => '/public/static/template1/images',
        '__TEMPLATE1_OTHER__'       => '/public/static/template1/other',
        '__UPLOADS__'        => '/public/uploads',
    ],
];
