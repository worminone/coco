<?php

namespace app\user\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class TeacherManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get|post} /user/TeacherManage/updateInfo 修改老师信息
     * @apiVersion 1.0.0
     * @apiName updateInfo
     * @apiGroup TeacherManage
     * @apiDescription 修改老师信息
     *
     * @apiParam {Int} user_id user_id（必填）
     * @apiParam {String} job_number 工号（选填）
     * @apiParam {Int} phone_tel 手机号（选填）
     * @apiParam {String} pic_url 头像（选填）
     * @apiParam {String} password 密码（选填）
     * @apiParam {String} student_number 学号(学籍号）
     * @apiParam {Int} gender 性别 0：保密 1：男 2：女
     * @apiParam {String} teacher_name 老师姓名
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/updateInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/TeacherManage/getInfo 获取老师信息
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                TeacherManage
     * @apiDescription          获取老师信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          teacher_id 老师ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "teacher_id": 20,
     * "teacher_name": "擎天柱",//老师名字
     * "pic_url": "http://image.zgxyzx.net/timg.jpg",//头像地址
     * "gender": "1",//性别
     * "phone_tel": "323232323",//电话
     * "job_number": "123123",//工号
     * "graduate_school": "",
     * "native_place": "福州",
     * "region": "福建省 福州市 鼓楼区",//学校地址
     * "school_name": "福州一中"//学校名称
     * }
     * }
     */
    public function getInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/getInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get|post} /user/TeacherManage/setAuth   授权激活老师和取消授权
     * @apiVersion 1.0.0
     * @apiName setTeacherAuth
     * @apiGroup TeacherManage
     * @apiDescription 授权激活老师和取消授权
     *
     * @apiParam {Int} user_id      老师的用户ID（必填）
     * @apiParam {Int} auth         1:增加授权，2:取消授权
     * @apiParam {Int} auth_type    1:学生授权,2:老师授权
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function setAuth()
    {
        $param = input('param.');
        $schoolApi = config('school_api');
        $url = $schoolApi . '/api/auth_userWW/updateAuth';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
