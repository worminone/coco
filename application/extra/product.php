<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 生产环境配置
return [

    'my_env' => 'product',
    'my_host' => 'zgxyzx.net',
    'college_api' => 'http://college.api.zgxyzx.net',
    'evaluate_api' => 'http://evaluate.api.zgxyzx.net',
    'student_app_api' => 'http://student.api.zgxyzx.net',
    'base_api' => 'http://base.api.zgxyzx.net',
    'gz_api' => 'http://gz.zgxyzx.net',
    'school_api' => 'http://school.api.zgxyzx.net',
    'ddzx_api' =>'http://ddzx.api.zgxyzx.net',
    'sign_key' => 'JF0XMw6XhwU8jXHH',
    // webAPi的来源域名验证
    'allow_orgin' => [
        'shengya.admin.zgxyzx.net',
        'base.api.zgxyzx.net',
        'guanwang.zgxyzx.net',
        'test.zgxyzx.net',
        'gz.zgxyzx.net',
        'college.front.zgxyzx.net',
        'guanwang.gaorong.net',
        'school.teacher.zgxyzx.net',
        'ddzx.html.zgxyzx.net'
    ],

    'cache' => [
        // 驱动方式
        'type' => 'Redis',
        'host' => '119.23.153.187',
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0
    ],

    'database' => [
        // 数据库类型
        'type' => 'mysql',
        // 服务器地址
        'hostname' => '10.24.166.236',
        // 数据库名
        'database' => 'ddzx_admin',
        // 用户名
        'username' => 'root',
        // 密码
        'password' => 'Jdi12ZndoIEj923JCN295',
        // 端口
        'hostport' => '',
        // 连接dsn
        'dsn' => 'mysql:dbname=ddzx_admin;host=10.24.166.236;charset=utf8',
        // 数据库连接参数
        'params' => [],
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库表前缀
        'prefix' => 'dd_',
        // 数据库调试模式
        'debug' => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy' => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate' => false,
        // 读写分离后 主服务器数量
        'master_num' => 1,
        // 指定从服务器序号
        'slave_no' => '',
        // 是否严格检查字段是否存在
        'fields_strict' => false,
        // 数据集返回类型
        'resultset_type' => 'array',
        // 自动写入时间戳字段
        'auto_timestamp' => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain' => false
    ]

]
;
