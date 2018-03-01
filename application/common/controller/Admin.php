<?php
/*
 * 运营总后台内部接口控制器的父类,会做权限检查
 * */

namespace app\common\controller;

use app\common\controller\Base;
use think\Cache;
use think\Request;

class Admin extends Base
{
    protected $uid;
    protected $userInfo;


    // apidoc -i svn\ddzx_admin_api\trunk\application\ -o svn\ddzx_admin_api\doc\apidoc\
    public function __construct()
    {
        parent::__construct();
        //token可以放在header里头，也可以放到get或者post里头
        $token = Request::instance()->header('token');
        $token = $token ? $token : input('request.token');

//        if (! $token) {
//            $this->response('-99999', '请先登录系统');
//        }
//         $this->userInfo = db('admin_user')->where(['token'=>$token])->find();
//        if (! $this->userInfo) {
//            $this->response('-99998', '尚未登录，请登录系统');
//        }
//
//        $this->uid = $this->userInfo['id'];
//        $lastActionTimeKey = 'last_action_time:' . $this->uid;
//        $lastActionTime = Cache::get($lastActionTimeKey);
//        $diffTime = time() - $lastActionTime;
//        if ($diffTime > config('login_expire')) {
////             $this->response('-99997', '登录已超时，请重新登录系统');
//        } else {
//            Cache::set($lastActionTimeKey, time());
//        }
////
//        //这里要判断后台用户访问该url的权限
//         $requestUri = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
//
//        //超级管理员拥有无限权限
//        if ($this->uid != 1) {
//            $this->checkAuth($requestUri, $this->uid);
//        }
    }

    /*
     * 用户权限的检查
     * */
    protected function checkAuth($url)
    {
        //所有用户都可以访问后台首页数据统计页面
        $uri = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;

        $freeAdmin = config('free_admin');
        //全部转换成小写
        $freeAdmin = array_map('strtolower', $freeAdmin);

        //不需要权限验证的接口
        if (in_array($uri, $freeAdmin)) {
            return true;
        }

        $menuId = db('menu')->where(['url'=>$url, 'status'=>1])->value('id');

//         aa($menuId);117
        //如果url不存在menu表默认是可以访问的
        if ($menuId) {
            $roleGroup = db('admin_user')->where('id =' . $this->uid)->value('role_id');
            $where = [];
            $where['id'] = ['in', explode(',', $roleGroup)];
            $where['status'] = 1;
            $rs = db('role')->where($where)->column('menu_id');

//             aa($rs);
            $menuAuth = [];
            foreach ($rs as $one) {
                $menuAuth = array_merge($menuAuth, explode(',', $one));
            }

//             aa($menuAuth);
            if (! in_array($menuId, $menuAuth)) {
                $this->response('-99996', '您无权访问');
            }
        }
        else {
            if (config('my_env') == 'test' || config('my_env') == 'product' ) {
//                 $this->response('-99996', '您无权访问');
            }

        }
    }
}
