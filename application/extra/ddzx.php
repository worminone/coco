<?php
// 项目自定义的公共配置文件

return [
    'max_aio_slide' => 10,      //一体机轮播图的数量设置
    'max_stu_app_slide' =>10,   //高中学生APP轮播图的数量设置

    'login_expire' => '90000', // 登录后如果什么都没有操作，那么会有一个超时时间

    //分页
    'pagesize' => 10,
    //后台key
    'admin_key'=>'Jd8234Ojd1ZHuw98WhsI1298JXn94',
    // 最大短信发送的次数
    'max_sms_send_times' => 3,
    // 手机短信白名单，白名单里的手机号码没有短信发送的限制
    'sms_white_list' => [
        '18950252043',
        '15060680296',
        '18695717578',
        '18030390556',
        '13043512219',
        '18065040062',
        '15959101089',
        '15060050549'
    ],

    'weixin' => [
        //高级功能-》开发者模式-》获取
        'app_id' => 'wx67ddae298562e7fc', //公众号appid
        'app_secret' => '4ba7abac8849f2abf5c2102b0bac71cb', //公众号app_secret
    ],

    //设定不要验签的方法，记住字符串都要小写
    'free_pass' => [
        'api/aio.AioTopic/AioTopic',          //一体机专题
        'api/aio.AioTopic/articleInfo',       //一体机文章
        'api/aio.AioTopic/getSystemInfo',     //密码登录
		'business/BusinessManage/exportExcel',
        'ad/index/statistics',                //下载广告数据的excel

    ],

    //不要权限验证的方法
    'free_admin' => [
        'index/home/statistics',              //总后台统计
        'auth/user/updatePassword',           //后台管理员更新自己的密码
        'auth/user/view',                     //后台管理员个人设置
        'auth/user/editAvatar',               //后台管理员更新自己的头像
        'system/SystemManage/weixinLogin',    //后台管理员绑定微信账号
        'system/SystemManage/setOpenId',      //后台管理员解绑微信账号
        'message/index/count',                //后台首页消息的数量
        'auth/user/index',                     //获取后台管理员列表
        'article/category/categorylist'
    ]

];
