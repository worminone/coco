<?php
namespace app\auth\controller;

use app\common\controller\Base;
use app\auth\model\Member;
use kuange\qqconnect\QC;
use think\Db;

class Login extends Base
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @api {get|post} /auth/login/login 用户登录
     * @apiVersion              1.0.0
     * @apiName                 login
     * @apiGroup                ADMINUSER
     * @apiDescription          后台管理员登录
     *
     * @apiParam {String}       user_name 管理员的登录名(手机)
     * @apiParam {String}       password 登录名密码
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     * @apiSuccess {String}     token 用户的token
     */
    public function login()
    {
        $userName = input('request.user_name');
        $password = input('request.password');

        if ($password && strlen($password) != 32) {
            $password = md5($password);
        }
        if (! $userName) {
            $this->response(-10090, '请输入登录名');
        }
        if (! $password) {
            $this->response(-10091, '请输入密码');
        }

        $where = "(user_name='$userName')";
        $count = db('admin_user')->where($where)->count();
        if ($count == 0) {
            $this->response(-10012, '该账号还未注册');
        }

        $where .= " AND password='$password'";

        $user = db('admin_user')->where($where)->find();
        if ($user) {
            if ($user['status'] == 0 ) {
                $this->response(-1, '该用户已经删除');
            } elseif($user['status'] == 2 ) {
                $this->response(-1, '该用户已经被冻结');
            } else {
                $userModel = new Member();
                $user = $userModel->getInfo($user['id']);
                $user['token'] = $userModel->loginSuccess($user);
                $this->response(1, '登录成功', $user);
            }

        } else {
            $this->response(-10095, '登录名或者密码错误');
        }
    }

    public function qqAction()
    {
        $qc = new QC();
        return redirect($qc->qq_login());
    }

    /**
     * @api {post} /auth/login/weixinLogin 用户微信登录
     * @apiVersion 1.0.0
     * @apiName weixinLogin
     * @apiGroup User
     * @apiDescription 用户微信登录
     * @apiParam {String} time 请求的当前时间戳.(原生APP端才需要).
     * @apiParam {String} sign 签名.(原生APP端才需要).
     * @apiParam {String} token 绑定的手机号码(必填).
     * @apiParam {int} user_id 用户ID (绑定的时候传，登录的时候不要这个参数，可选)
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function weixinLogin()
    {
        $redirect_uri = 'http://ddzx.api.' . config('my_host') . '/auth/Login/redirectUrl';
        $state = 'start';
        $callback = urlencode(config('weixin.redirect_uri'));
        $wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid=" . config('weixin.app_id') .
            "&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
        $content = file_get_contents($wxurl);
        preg_match('/<img[^>]*src=[\'"]*([^\'"]*)[\'"][^>]*>/is', $content, $img);
        // $img = 'https://open.weixin.qq.com'.$img[1];
        if ($img) {
            $this->response('1', '二维码获取成功', $wxurl);
        } else {
            $this->response('-1', '二维码已失效');
        }
    }

    /**
     * @api {post} /auth/login/bindWeixin 绑定微信
     * @apiVersion 1.0.0
     * @apiName bindWeixin
     * @apiGroup User
     * @apiDescription 绑定微信
     * @apiParam {String} time 请求的当前时间戳.(原生APP端才需要).
     * @apiParam {String} sign 签名.(原生APP端才需要).
     * @apiParam {String} token 绑定的手机号码(必填).
     * @apiParam {int} user_id 用户ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function bindWeixin()
    {
        $id = input('get.user_id', '', 'int');
        $url = '?id=' . $id;
        $member_info = DB::name('AdminUser')->where(['id' => $id])->find();
        if ($member_info['open_id']) {
            $this->response('-1', '用户已绑定！');
        }
        $redirect_uri = 'http://ddzx.api.' . config('my_host') . '/auth/Login/redirectUrl';
        $redirect_uri = $redirect_uri . $url;
        $state = 'start';
        $callback = urlencode(config('weixin.redirect_uri'));
        $wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid=" . config('weixin.app_id') .
            "&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
        $content = file_get_contents($wxurl);
        preg_match('/<img[^>]*src=[\'"]*([^\'"]*)[\'"][^>]*>/is', $content, $img);
        // $img = 'https://open.weixin.qq.com'.$img[1];
        if ($img) {
            $this->response('1', '二维码获取成功', $wxurl);
        } else {
            $this->response('-1', '二维码已失效');
        }
    }

    /**
     * @api {post} /auth/login/redirectUrl 微信扫码后的回调地址
     * @apiVersion 1.0.0
     * @apiName redirectUrl
     * @apiGroup User
     * @apiDescription 微信扫码后的回调地址
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function redirectUrl()
    {
        if ($_GET['state'] != 'start') {
            $this->response('-1', '提交参数有误');
        }
        $id = input('get.id', '', 'int');

        $app_id = config('weixin.app_id');
        $app_secret = config('weixin.app_secret');
        $return_api = config('return_api');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $app_id . '&secret=' . $app_secret .
            '&code=' . $_GET['code'] . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($json, 1);
        //得到 access_token 与 openid
        if(!isset($arr['access_token'])) {
            $this->response('1', '参数错误');
        }
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $arr['access_token'] .
            '&openid=' . $arr['openid'] . '&lang=zh_CN';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($json, 1);
        // aa($arr);
        $member_info = DB::name('AdminUser')->where(['open_id' => $arr['openid']])->find();
        if (!empty($id)) {
            if ($member_info) {
                header('Location:' . $return_api . '/#/system/systemPersonal?code=-3');
                exit();
            }
            $data = [
                'open_id' => $arr['openid'],
                'wx_nickname' => $arr['nickname'],
                'wx_headimgurl' => $arr['headimgurl'],
            ];
            $res = DB::name('AdminUser')->where(['id' => $id])->update($data);
            if ($res > 0) {
                // $this->response('1', '绑定成功', 'Location:' . $return_api . '/#/system/systemPersonal');
                header('Location:' . $return_api . '/#/system/systemPersonal?code=-1');
                exit();
            } else {
                header('Location:' . $return_api . '/#/system/systemPersonal');
                exit();
            }
        } else {
            if (empty($member_info)) {
                header('Location:' . $return_api . '/#/login?code=-2');
                exit();
                // $this->response('1', '还未绑定个人微信哦，请使用手机号登录'.$arr['openid']);
            } else {
                $member_status = DB::name('AdminUser')->where(['open_id' => $arr['openid'], 'status'=>1])->find();
                if (empty($member_status)) {
                    header('Location:' . $return_api . '/#/login?code=-5');
                    exit();
                }
                $userModel = new Member();
                $user = $userModel->getInfo($member_info['id']);
                $token = $userModel->loginSuccess($user);
                $member_info['token'] = $token;
                $output = json_encode($member_info);
                $puturl = 'url=' . $output;
                header('Location:' . $return_api . '/#/LoginQrcode?' . $puturl);
                exit();
            }
        }
    }

    /**
     * @api {post} /auth/login/setOpenId 解绑微信
     * @apiVersion 1.0.0
     * @apiName setOpenId
     * @apiGroup User
     * @apiDescription 解绑微信
     * @apiParam {String} time 请求的当前时间戳.(原生APP端才需要).
     * @apiParam {String} sign 签名.(原生APP端才需要).
     * @apiParam {String} token 绑定的手机号码(必填).
     * @apiParam {int} user_id 用户ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function setOpenId()
    {
        $id = input('get.user_id', '', 'intval');
        $data = [
            'open_id' => '',
            'wx_nickname' => '',
            'wx_headimgurl' => '',
        ];
        $res = DB::name('AdminUser')->where(['id' => $id])->update($data);
        if ($res !== false) {
            $this->response('1', '解绑成功');
        } else {
            $this->response('-1', '解绑失败');
        }
    }

}
