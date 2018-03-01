<?php

namespace app\user\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class StudentManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /user/StudentManage/getInfo 获取学生信息
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                StudentManage
     * @apiDescription          获取学生信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          student_id 学生ID
     * @apiParam {Int}          user_id 二选一
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "student_id": 184,
     * "user_id": 21500,
     * "gender": "1",//性别
     * "phone_tel": "18059149313",//手机
     * "realname": "1111",//真实姓名
     * "student_number": "1111",//学号
     * "pic_url": "http://image.zgxyzx.net/user.png",//头像
     * "region": "福建省 福州市 鼓楼区",//学校地址
     * "school_name": "福州一中"//学校名称
     * }
     * }
     */
    public function getInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/StudentManage/getInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/StudentManage/updateInfo 修改学生信息
     * @apiVersion 1.0.0
     * @apiName updateInfo
     * @apiGroup StudentManage
     * @apiDescription 修改学生信息
     *
     * @apiParam {Int} user_id user_id（必填）
     * @apiParam {Int} phone_tel 手机号（选填）
     * @apiParam {String} pic_url 头像（选填）
     * @apiParam {String} password 密码（选填）
     * @apiParam {String} student_number 学号(学籍号）
     * @apiParam {Int} gender 0：保密 1：男 2：女
     * @apiParam {String} realname 真实姓名
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/StudentManage/updateInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/StudentManage/setAuth   授权激活学生和取消授权
     * @apiVersion 1.0.0
     * @apiName setStudentAuth
     * @apiGroup StudentManage
     * @apiDescription 授权激活学生和取消授权
     *
     * @apiParam {Int} user_id          学生的用户ID（必填）
     * @apiParam {Int} auth             1:增加授权，2:取消授权
     * @apiParam {Int} auth_type        1:学生授权,2:老师授权
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function setAuth()
    {
        $param = input('param.');
        $schoolApi = config('school_api');
        $url = $schoolApi . '/api/auth_user/updateAuth';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
