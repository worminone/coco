<?php

namespace app\user\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class ExpertManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /user/ExpertManage/getList 获取专家列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup user
     * @apiDescription 获取专家列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} experts_name 用户名.
     * @apiParam {Int}    pagesize  当前数量.
     * @apiParam {Int}    page      当前页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id:          "账号ID",
     *      experts_name:"账号",
     *      status:      "状态（1正常 0冻结）",
     *      update_time: "更新时间"
     *      experts_name  导师名字
     *      sex           性别
     *      auth          资质
     * }
     * ]
     * }
     */
    public function getList()
    {
        $param['experts_name'] = input('param.experts_name', '', 'htmlspecialchars');
        $param['pagesize'] = input('param.pagesize', '10', 'int');
        $param['page'] = input('param.page', '1', 'int');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /user/ExpertManage/addInfo 新增专家信息
     * @apiVersion 1.0.0
     * @apiName addInfo
     * @apiGroup user
     * @apiDescription 新增专家信息
     *
     * @apiParam {String} time            请求的当前时间戳.
     * @apiParam {String} sign            签名.
     * @apiParam {String} user_name       用户名
     * @apiParam {Int}    experts_id      专家ID
     * @apiParam {String} password        密码
     * @apiParam {Int}    sex             性别
     * @apiParam {String} pic_url         头像
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addInfo()
    {
        $param = input('param.');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/addInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/ExpertManage/editInfo 获取专家信息
     * @apiVersion 1.0.0
     * @apiName editInfo
     * @apiGroup user
     * @apiDescription 获取专家信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    user_id   账号ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    user_id: 用户id,
     *    experts_name: "专家姓名",
     *    pic_url: "图片地址",
     *    gender: 性别（1男 2 女）,
     *    user_name   帐号
     * }
     * ]
     * }
     */
    public function editInfo()
    {
        $param['user_id'] = input('param.user_id');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/editInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/ExpertManage/saveInfo 修改专家信息
     * @apiVersion 1.0.0
     * @apiName saveInfo
     * @apiGroup user
     * @apiDescription 修改专家信息
     *
     * @apiParam {String} time            请求的当前时间戳.
     * @apiParam {String} sign            签名.
     * @apiParam {int}    user_id         专家ID
     * @apiParam {String} user_name       账号
     * @apiParam {String} password        密码
     * @apiParam {String} sex             性别
     * @apiParam {int}    experts_id      专家详细信息ID
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveInfo()
    {
        $param = input('param.');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/saveInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /user/ExpertManage/delInfo 删除专家信息
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup user
     * @apiDescription 删除专家信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} type_id 测评ID.
     * @apiParam {String} status: 状态 （1 正常 0 删除）.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delInfo()
    {
        $param['user_id'] = input('param.user_id');
        $param['status'] = input('param.status');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/delInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

}
