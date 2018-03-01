<?php

namespace app\data\controller;

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
     * @api {get|post} /data/TeacherManage/getList 导师列表
     * @apiVersion 1.0.0
     * @apiName getStuInfo
     * @apiGroup TeacherManage
     * @apiDescription 导师列表
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 导师信息.
     */
    public function getList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /data/TeacherManage/updateInfo 修改老师信息
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
     * @api {get|post} /data/TeacherManage/getBaseList 全国老师数据库
     * @apiVersion 1.0.0
     * @apiName getBaseList
     * @apiGroup TeacherManage
     * @apiDescription 导师列表
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {String}       native_place 籍贯(可选)
     * @apiParam {Int}          region_id 城市ID(可选)
     * @apiParam {Int}          cur_status 当前状态（0：在校  1：已离校）
     * @apiParam {Int}          gender 0：保密 1：男 2：女',
     * @apiParam {String}       begin_addtime 添加开始时间（可选）
     * @apiParam {String}       end_addtime 添加结束时间（可选）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 导师信息.
     * @apiSuccessExample  {json} Success-Response:*
     * "teacher_id": 20,//老师ID
     * "user_id":1,//用户ID
     * "pic_url": null,//头像
     * "teacher_name": "擎天柱",//老师姓名
     * "gender": null,//0：保密 1：男 2：女
     * "school_name": "1",//学校名称
     * "native_place": null,//籍贯
     * "job_number": "123123",//工号
     * "phone_tel": null,//手机号
     * "cur_status": 0//当前状态（0：在校  1：已离校）
     */
    public function getBaseList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/getBaseList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/TeacherManage/getInfo 获取老师信息
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
     * "teacher_id": 22,
     * "teacher_name": "李花花哈哈",
     * "pic_url": "http://image.zgxyzx.net/timg.jpg",//头像
     * "gender": "1",
     * "phone_tel": "1255454131",
     * "born": "15564454",//出生年月
     * "max_educational": 3,//最高学历(0：博士 1：硕士 :2：研究生 3：本科 4：大专 5：中专 6：高中）
     * "graduate_school": "大学",//毕业学校
     * "cur_status": 0,//当前状态（0：在校  1：已离校）
     * "nationality": "中国",//国籍
     * "political_status": "团员",
     * "seniority": 22,//教龄
     * "qualification_number": "导师资格证编号",
     * "working_time": "2017-09-13",
     * "work_experience": "工作经历",
     * "graduate_major": "毕业专业"
     * `nation` ; '民族',
     * `native_place` ： '籍贯',
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
     * @api {post} /api/TeacherManage/delInfo 批量删除老師
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup TeacherManage
     * @apiDescription 批量删除老師
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} teacher_id 学校ID(批量用","隔开)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功"
     * }
     */
    public function delInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/delInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /data/TeacherManage/tchClass 教师在校信息
     * @apiVersion 1.0.0
     * @apiName tchClass
     * @apiGroup TeacherManage
     * @apiDescription 教师在校信息
     *
     * @apiParam {String} user_id 用户id.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 教师在校信息.
     * @apiSuccess {String} job_number 工号.
     * @apiSuccess {String} business_name 现任职务.
     * @apiSuccess {String} class_name 教学班级.
     */
    public function tchClass()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/TeacherManage/tchClass';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
