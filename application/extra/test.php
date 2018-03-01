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

// 测试环境配置
return [

    'my_env' => 'test',
    'my_host' => 'dadaodata.com',
    'college_api' => 'http://college.api.dadaodata.com',
    'evaluate_api' => 'http://evaluate.api.dadaodata.com',
    'student_app_api' => 'http://student.api.dadaodata.com',
    'base_api' => 'http://base.api.dadaodata.com',
    'gz_api' => 'http://gz.dadaodata.com',
    'school_api' => 'http://school.api.dadaodata.com',
    'return_api' => 'http://ddzx.html.dadaodata.com',
    'evaluate_api' => 'http://evaluate2.api.dadaodata.com',
    'experts_api' => 'http://expert.api.dadaodata.com',
    'gw_api' => 'http://test1.dadaodata.com',
    // 'return_api' => 'http://localhost:8080/',
    'sign_key' => 'JF0XMw6XhwU8jXHH',
    // webAPi的来源域名验证
    'allow_orgin' => [
        'shengya.admin.dadaodata.com',
        'base.api.dadaodata.com',
        'guanwang.dadaodata.com',
        'test.dadaodata.com',
        'gz.dadaodata.com',
        'college.front.dadaodata.com',
        'guanwang.gaorong.net',
        'school.teacher.dadaodata.com',
        'ddzx.html.dadaodata.com',
        'experts.html.dadaodata.com'
    ],

    'aio_config'=> [
        'new_category'=> '1',         //一体机 新闻分类ID
        'activity_category'=>'3',     //一体机 活动分类ID
        'video_category'=>'14',       //一体机 视频分类ID
        'voluntarily_category'=>'129', //一体机 自愿填报分类ID
        'career_category'=>'119',      //一体机 生涯规划分类ID
        'eighteen_category'=>'137',   //一体机 生涯十八讲分类ID
    ],
    'gw_config'=> [
        'industry_category'=> '186', //官网 行业新闻分类ID
        'media_category'=>'187',     //官网 媒体分类ID
        'company_category'=>'185',  //官网 公司分类ID
        'video_category'=>'189',  //官网 视频分类ID
        'slide_category'=>'192',  //官网 轮播图分类ID
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
        'hostname' => '119.23.149.220',
        // 数据库名
        'database' => 'ddzx_admin',
        // 用户名
        'username' => 'liuyouping',
        // 密码
        'password' => 'j9hr3n3e',
        // 端口
        'hostport' => '',
        // 连接dsn
        'dsn' => 'mysql:dbname=ddzx_admin;host=119.23.149.220;charset=utf8',
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
