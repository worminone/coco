<?php

namespace app\user\controller;

use think\Db;
use \app\business\model\Business;
use think\Request;
use app\common\controller\Admin;

class UserManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /user/UserManage/getUserId 根据token查询用户ID
     * @apiVersion              1.0.0
     * @apiName                 getUserId
     * @apiGROUP                UserManage
     * @apiDescription          查看用户列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": {
     * "user_id": 1
     * }
     * }
     */
    public function getUserId()
    {
        $data = array();
        $data['user_id'] = !empty($this->uid) ? $this->uid : 0;
        $this->response(1, '获取成功', $data);
    }

    /**
     * @api {post} /user/UserManage/getList 查看用户列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                UserManage
     * @apiDescription          查看用户列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          type 1 高中学校用户 2 老师用户 3 学生用户 4 家长用户 5 院校用户 6 高中一体机,默认为1
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          is_active 0 冻结 1 激活
     * @apiParam {Int}          is_formal 0 体验 1 签约
     * @apiParam {Int}          is_auth 0 已授权 1 未授权
     * @apiParam {Int}          end_reg_time 创建结束时间
     * @apiParam {Int}          begin_reg_time 创建开始时间
     * @apiParam {Int}          province_id 省ID
     * @apiParam {Int}          region_id 区域ID
     * @apiParam {Int}          gender 性别 0 保密 1 男 2 女
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          page_num 总页数
     *                          page 页码<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {//高中学校用户
     * "user_id": 12,
     * "user_name": "fzlsy222",
     * "reg_time":1473231696,//注册时间
     * "school_id": 10,
     * "province": "福建省",
     * "city": "福州市",
     * "county": "鼓楼区",
     * "pic_url": "",//校徽
     * "school_name": "1",
     * "contact_address": "福建省 福州市 鼓楼区 ",//学校地址
     * "is_active": 0,//0正常 冻结
     * "is_formal":0 签约账户 1 体验用户
     * },
     * {//老师信息
     * "province": "福建省",//省份
     * "city": "福州市",//城市
     * "county": "鼓楼区",//区县
     * "user_id": 222,
     * "school_name": "1",//学校名称
     * "teacher_name": "傻傻哈",//老师名称
     * "gender": "1",//0：保密 1：男 2：女
     * "user_name": "vv24eMeMMb",//用户账号
     * "is_active": 0, //是否激活(0:否 1：是）
     * "job_number":"sdf"//工号
     * "pic_url":''//头像
     * },
     * {//学生信息
     * "province": "",
     * "city": "",
     * "county": "",
     * "user_id": 2,
     * "school_name": "1",
     * "realname": "大黄蜂3",//学生姓名
     * "gender": "2",
     * "user_name": "ovadmin",
     * "student_number": "201103150115",//学号
     * "is_active": 0
     * },
     * {//家长信息
     * "user_id":"",//用户ID
     * "pic_url":"",//用户头像
     * "name": "lisi",//家长姓名
     * "phone_tel": null,//家长的手机号码
     * "reg_time":123213213,//创建时间
     * "relation": 1,
     * "relation_name": "父亲",//与学生关系
     * "is_active": 0,//是否激活 0 未激活 1 激活
     * "realname": "大黄蜂4",//学生姓名
     * "student_gender": 0,//学生性别
     * "student_number": "201103150116",//学生学号
     * "school_name": "1",//学校姓名
     * "user_name": "fzlsy123",//家长账号
     * },
     * {//院校用户
     * "user_id": 4851,
     * "user_name": "13105977770",//用户帐号
     * "province": "",
     * "city": "",
     * "county": "",
     * "is_active": 1,
     * "title": "北京大学电饭锅"//院校名称
     * },*
     */
    public function getList()
    {
        $type = input('type', 1);
        $keyword = input('keyword', '', 'htmlspecialchars');
        $pagesize = input('pagesize', 20, 'int');
        $page = input('page', 1, 'int');
        $is_active = input('is_active', "");
        $is_formal = input('is_formal', "");
        $end_reg_time = input('end_enter_time', "");
        $begin_reg_time = input('begin_enter_time', "");
        $region_id = input('region_id', "");
        $province_id = input('province_id', "");
        $gender = input('gender', "");
        $isAuth = input('is_ath', "");
        $list = array();
        switch ($type) {
            case 1://高中学校用户
                $url = config('school_api') . '/api/SchoolManage/getStudentList';
                break;
            case 2://老师用户
                $url = config('school_api') . '/api/TeacherManage/getList';
                break;
            case 3://学生用户
                $url = config('school_api') . '/api/StudentManage/getList';
                break;
            case 4://家长用户
                $url = config('school_api') . '/api/ParentManage/getList';
                break;
            case 5://院校用户
                $url = config('school_api') . '/api/CollegeManage/getUserList';
                break;
            default:
        }

        $data = [];
        $data['page'] = $page;
        $data['pagesize'] = $pagesize;
        ($is_active != "") && $data['is_active'] = $is_active;
        ($keyword != "") && $data['keyword'] = $keyword;
        ($is_formal != "") && $data['is_formal'] = $is_formal;
        ($end_reg_time != "") && $data['end_enter_time'] = $end_reg_time;
        ($begin_reg_time != "") && $data['begin_enter_time'] = $begin_reg_time;
        ($region_id != "") && $data['region_id'] = $region_id;
        ($province_id != "") && $data['province_id'] = $province_id;
        ($gender != "") && $data['gender'] = $gender;
        ($isAuth != "") && $data['is_ath'] = $isAuth;
        $list = curl_api($url, $data, 'post', 0);

        $this->response(1, '获取成功', !empty($list['data']) ? $list['data'] : array('count' => 0));
    }

    /**
     * @api {get|post} /user/UserManage/updateStatus 批量冻结恢复用户列表
     * @apiVersion 1.0.0
     * @apiName updateStatus
     * @apiGroup UserManage
     *
     * @apiDescription 批量冻结或恢复用户
     *
     * @apiParam {String} user_id 用户ID 多个用","分隔 （不为空时以下的条件无效）
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function updateStatus()
    {
        $param = input('param.');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/updateStatus';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
