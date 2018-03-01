<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class ParentManage extends Admin
{
    /**
     * @api {get|post} /data/ParentManage/getList 家长列表
     * @apiVersion 1.0.0
     * @apiName getStuInfo
     * @apiGroup ParentManage
     * @apiDescription 家长列表
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          is_active 0 冻结 1 激活
     * @apiParam {Int}          end_reg_time 创建结束时间
     * @apiParam {Int}          begin_reg_time 创建开始时间
     * @apiParam {Int}          region_id 区域ID
     * @apiParam {Int}          gender 性别 0 保密 1 男 2 女
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 家长信息.
     * @apiSuccessExample  {json} Success-Response:
     *  {
     *      "id": 1,
     *      "user_id": 54,
     *      "student_user_id": 21517,
     *      "phone_tel": "15880086895",//家长电话
     *      "name": "李梅",//家长姓名
     *      "relation": 1, //家长关系
     *      "avatar": "1",//家长头像
     *      "realname": "大黄蜂22",//学生名字
     *      "student_gender": null,//学生性别
     *      "student_number": "10008",//学生学号
     *      "school_name": null,学校名称
     *      "region": "  "//学校地址
     *      "is_active": 1, //是否激活(0:否 1：是）
     *      "work": null,   //工作单位
     *      "job": null,   //家长职业职位
     *      "graduated": null,  //家长毕业院校
     *      "major": null,  //家长专业
     *      "views_on_major": null,  //简单描述对您的专业的看法
     *      "views_on_job": null,  //简单描述对您的职业的看法
     *      "visit": 1   //能不能参观您所在单位1能2不能
     *      "share": 1,   //愿意与学生分享职场经验，1愿意2考虑
     *      "contact_address": "软件园G区",
     *      "region": "福建省 福州市 鼓楼区"
     * },
     */
    public function getList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/ParentManage/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /data/ParentManage/updateInfo 修改家长信息
     * @apiVersion 1.0.0
     * @apiName updateInfo
     * @apiGroup ParentManage
     * @apiDescription 修改家长信息
     *
     * @apiParam {Int} user_id user_id（必填）
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
     * @api {get|post} /data/ParentManage/getInfo 家长信息
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
     *
     *      "id": 1,
     *      "user_id": 54,
     *      "student_user_id": 21517,
     *      "phone_tel": "15880086895",//家长电话
     *      "name": "李梅",//家长姓名
     *      "relation": 1, //家长关系
     *      "avatar": "1",//家长头像
     *      "realname": "大黄蜂22",//学生名字
     *      "student_gender": null,//学生性别
     *      "student_number": "10008",//学生学号
     *      "school_name": null,学校名称
     *      "region": "  "//学校地址
     *      "is_active": 1, //是否激活(0:否 1：是）
     *      "work": null,   //工作单位
     *      "job": null,   //家长职业职位
     *      "graduated": null,  //家长毕业院校
     *      'earnings': null     //年收入档次
     *      'working_life': null  //工作年限
     *      "major": null,  //家长专业
     *      "views_on_major": null,  //简单描述对您的专业的看法
     *      "views_on_job": null,  //简单描述对您的职业的看法
     *      "visit": 1   //能不能参观您所在单位1能2不能
     *      "share": 1,   //愿意与学生分享职场经验，1愿意2考虑
     *      "contact_address": "软件园G区",
     *      "region": "福建省 福州市 鼓楼区"
     * }
     * }
     */
    public function getInfo()
    {
        $param['id'] = input('param.id');
        $school_api = config('school_api');
        $url = $school_api . '/api/ParentManage/getInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/ParentManage/delInfo 批量删除家长
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup ParentManage
     * @apiDescription 批量删除家长
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} user_id 家长ID(批量用","隔开)
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
        $url = $school_api . '/api/ParentManage/delInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /api/ParentManage/exportParent 导出家长列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup ParentManage
     * @apiDescription 导出家长列表
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          is_active 0 冻结 1 激活
     * @apiParam {Int}          end_reg_time 创建结束时间
     * @apiParam {Int}          begin_reg_time 创建开始时间
     * @apiParam {Int}          region_id 区域ID
     * @apiParam {Int}          gender 性别 0 保密 1 男 2 女
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function exportParent()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/ParentManage/exportParent';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);


    }
}
