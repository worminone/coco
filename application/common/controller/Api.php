<?php
//对外的api数据的输出

namespace app\common\controller;

use app\common\controller\Base;
use think\Request;
use think\Cache;

class Api extends Base
{
    protected $userInfo;            //登录用户信息


    public function __construct()
    {
        parent::__construct();
        //token可以放在header里头，也可以放到get或者post里头
        $token = Request::instance()->header('token');
        $token = $token ? $token : input('param.token');
        // if (! $token) {
        //     $this->response('-99999', '请先登录系统');
        // }

        // $this->userInfo = Cache::get($token);
        // if (! $this->userInfo) {
        //     $this->response('-99998', '请先登录系统');
        // }
    }


}
