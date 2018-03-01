<?php

namespace app\system\controller;

use think\Db;
use \app\system\model\XyzxConfig;
use think\Request;
use app\common\controller\Admin;
use app\article\model\Common;
class SystemManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /system/SystemManage/getInfo 查看校园在线官网配置
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                SystemManage
     * @apiDescription          查看招商列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiParam {Int}          status  网站开关，1:网站可以访问，0:网站显示关闭状态
     * @apiParam {String}       title   网站标题，一句话介绍概括
     * @apiParam {String}       watchword 网站口号,网站宣传语，一句话介绍概括
     * @apiParam {String}       keyword   网站搜索引擎关键词
     * @apiParam {String}       description   网站搜索引擎描述
     * @apiParam {String}       logo   网站LOGO,299*95
     * @apiParam {String}       img404   404图片,500*300
     * @apiParam {String}       copyright   版权信息
     * @apiParam {String}       icp_num   网站备案号
     * @apiParam {String}       statistics_code   站长统计
     * @apiParam {String}       company_name   公司名称
     * @apiParam {String}       address   公司地址
     * @apiParam {String}       intro   公司简介
     * @apiParam {String}       email   公司邮箱
     * @apiParam {String}       tel   公司电话
     * @apiParam {String}       customer_qq   客服 QQ
     * @apiParam {String}       company_qq   公司qq群
     * @apiParam {String}       xyzx_pubic_wechat   校园在线公众号二维码
     * @apiParam {String}       xyzx_service_wechat   校园在线微信服务号
     * @apiParam {String}       ddzx_volunteer_wechat   大道之行志愿公众号二维码
     * @apiParam {String}       ddzx_public_wechat   大道之行微信公众号
     * @apiParam {String}       download_qr   IOS和安卓下载二维码
     * @apiParam {String} content_mail 内容投稿
     * @apiParam {String} ad_mail 广告合作
     * @apiParam {String} business_mail 招商邮箱
     * @apiParam {String} business_phone  招商电话'
     * @apiParam {String} user_mail '用户支持
     */
    public function getInfo()
    {
        $data = model('XyzxConfig')->getInfo();
        if ($data) {
            $this->response('1', '获取成功', $data);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/SystemManage/updateInfo 修改配置
     * @apiVersion              1.0.0
     * @apiName                 updateInfo
     * @apiGROUP                SystemManage
     * @apiDescription          修改配置
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          status  网站开关，1:网站可以访问，0:网站显示关闭状态
     * @apiParam {String}       title   网站标题，一句话介绍概括
     * @apiParam {String}       watchword 网站口号,网站宣传语，一句话介绍概括
     * @apiParam {String}       keyword   网站搜索引擎关键词
     * @apiParam {String}       description   网站搜索引擎描述
     * @apiParam {String}       logo   网站LOGO,299*95
     * @apiParam {String}       img404   404图片,500*300
     * @apiParam {String}       copyright   版权信息
     * @apiParam {String}       icp_num   网站备案号
     * @apiParam {String}       statistics_code   站长统计
     * @apiParam {String}       company_name   公司名称
     * @apiParam {String}       address   公司地址
     * @apiParam {String}       intro   公司简介
     * @apiParam {String}       email   公司邮箱
     * @apiParam {String}       tel   公司电话
     * @apiParam {String}       customer_qq   客服 QQ
     * @apiParam {String}       company_qq   公司qq群
     * @apiParam {String}       xyzx_pubic_wechat   校园在线公众号二维码
     * @apiParam {String}       xyzx_service_wechat   校园在线微信服务号
     * @apiParam {String}       ddzx_volunteer_wechat   大道之行志愿公众号二维码
     * @apiParam {String}       ddzx_public_wechat   大道之行微信公众号
     * @apiParam {String}       download_qr   IOS和安卓下载二维码
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function updateInfo()
    {
        $info = input('param.');
        if (empty($info)) {
            $this->response('-1', '参数不能为空');
        }
        $data = model('XyzxConfig')->getInfo();
        if ($data) {
            DB::name('xyzx_config')->where(['id' => $data['id']])->update($info);
        } else {
            DB::name('xyzx_config')->insert($info);
        }
        $this->response('1', '修改成功');
    }
    /**
     * @api {post} /system/SystemManage/HomeCoverList 一体机首页封面列表
     * @apiVersion              1.0.0
     * @apiName                 HomeCoverList
     * @apiGROUP                SystemManage
     * @apiDescription          一体机首页封面列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}       term_type 终端类型（1高中升学一体机,2校园在线APP,3校园在线官网）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: 封面ID.
     *      title 封面标题.
     *      term_type 终端类型.
     *      img_url 封面地址.(密码)
     * }    
     * ]
     * }
     *
     */
    public function HomeCoverList()
    {
        $term_type = input('param.term_type','1', 'intval');
        $where['term_type'] = $term_type;
        $list = model('XyzxConfig')->getHomeCoveList($where);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/SystemManage/saveHomeCover 修改一体机首页封面信息
     * @apiVersion              1.0.0
     * @apiName                 saveHomeCover
     * @apiGROUP                SystemManage
     * @apiDescription          修改一体机首页封面信息
     *
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id: 封面ID.
     * @apiParam {String}       img_url 封面地址.
     *      
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function saveHomeCover()
    {
        $info = input('param.');
        $common = new Common;
        if($info['id'] == 21) {
            if($info['img_url'] =='') {
                $h_info = Db::name('HomeCover')->where(['id'=>21])->find();
                $info['img_url'] = $h_info['img_url'];
            } else {
                $info['img_url'] = md5($info['img_url']);
            }

        }
        $res = Db::name('HomeCover')->update($info);
        $common->sendPushToR40('','UPDATE_TERM_MENU');
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

        /**
     * @api {post} /system/SystemManage/weixinLogin 用户微信登录
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
        $id = input('get.user_id', '', 'int');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/weixinLogin';
        $param['user_id'] = $id;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post}  /system/SystemManage/setOpenId 解绑微信
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
        $id = input('get.user_id', '', 'int');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/setOpenId';
        $param['user_id'] = $id;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


}
