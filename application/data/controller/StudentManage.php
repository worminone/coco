<?php

namespace app\data\controller;

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
     * @api {get|post} /data/StudentManage/getList 学生列表
     * @apiVersion 1.0.0
     * @apiName getStuInfo
     * @apiGroup StudentManage
     * @apiDescription 学生列表
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 学生信息.
     */
    public function getList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/StudentManage/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /data/StudentManage/getBaseList 全国学生数据库
     * @apiVersion 1.0.0
     * @apiName getBaseList
     * @apiGroup StudentManage
     * @apiDescription 全国学生数据库
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 学生信息.
     * @apiSuccessExample  {json} Success-Response:*
     * "list": [
     * {
     * "student_id": 48,//学生ID
     * "user_id": null,//用户ID
     * "student_number": "801110315021251",//学号
     * "realname": "大黄蜂",//真名
     * "gender": null,//0：保密 1：男 2：女
     * "school_name": "1",//学校名称
     * "split_class":'1',//分级(0:初中  1：普高 2：中专
     * "property": 0,//学校属性(0:公立 1：私立）
     * "level_id": 1,//
     * "home_address": null,//家庭地址
     * "addtime": 1501667835,//提交时间
     * "is_active": null//是否激活(0:否 1：是）
     * },
     */
    public function getBaseList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/StudentManage/getBaseList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/StudentManage/getInfo 获取学生信息
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                StudentManage
     * @apiDescription          获取学生信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          student_id 学生ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "student_id": 48,
     * "user_id": null,
     * "gender": null,
     * "country_id": 0,
     * "phone_tel": null,//联系电话
     * "nation": null,
     * "native_place": null,
     * "political": null,
     * "class_id": 47,
     * "birthday": null,
     * "addtime": 1501667835,
     * "student_number": "801110315021251",//学号
     * "cur_status": 0,//当前状态(0:在读 1：已转班 2：已经辍学 3：已经转校）
     * "section_name": "高三",
     * "class_num_name": "4班",
     * "class_name": "高三(4)班"
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
     * @api {get|post} /data/StudentManage/updateInfo 修改学生信息
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
     * @api {post} /data/StudentManage/delInfo 批量删除学生
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup StudentManage
     * @apiDescription 批量删除学生
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} student_id 学生ID(批量用","隔开)
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
        $url = $school_api . '/api/StudentManage/delInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    /**
     * @api {get|post} /data/StudentManage/getStuParentInfo 学生家长信息
     * @apiVersion 1.0.0
     * @apiName getStuParentInfo
     * @apiGroup StudentManage
     * @apiDescription 学生家长信息
     *
     * @apiParam {String} student_user_id 学生id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 学生家长信息.
     */
    public function getStuParentInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/Stuinfo/getStuParentInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
