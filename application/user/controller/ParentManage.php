<?php

namespace app\user\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class ParentManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get|post} /user/ParentManage/updateInfo 修改家长信息
     * @apiVersion 1.0.0
     * @apiName updateInfo
     * @apiGroup ParentManage
     * @apiDescription 修改家长信息
     *
     * @apiParam {Int} id id（必填）
     * @apiParam {Int} user_id 用户ID（选填）
     * @apiParam {Int} phone_tel 手机号（选填）
     * @apiParam {String} avatar 头像（选填）
     * @apiParam {String} password 密码（选填）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/ParentManage/updateInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/ParentManage/getInfo 家长信息
     * @apiVersion 1.0.0
     * @apiName getInfo
     * @apiGroup ParentManage
     * @apiDescription 家长信息
     *
     * @apiParam {Int}  id 家长自增ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 家长信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "id": 1,
     * "user_id": 54,
     * "student_user_id": 21517,
     * "phone_tel": "15880086895",//家长电话
     * "name": "李梅",//家长姓名
     * "relation": 1,
     * "avatar": "1",//家长头像
     * "realname": "大黄蜂22",//学生名字
     * "student_gender": null,//学生性别
     * "student_number": "10008",//学生学号
     * "school_name": null,学校名称
     * "region": "  "//学校地址
     * }
     * }
     */
    public function getInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/ParentManage/getInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

}
