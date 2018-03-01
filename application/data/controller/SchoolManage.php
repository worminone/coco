<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class SchoolManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/SchoolManage/getList 获取高中学校列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                SchoolManage
     * @apiDescription          获取高中学校列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          enterflag 入驻状态（可选）
     * @apiParam {Int}          region_id 地域（可选）
     * @apiParam {Int}          district_id 所在区ID（可选）
     * @apiParam {Int}          split_class 类别（可选）
     * @apiParam {Int}          property 性质（可选）
     * @apiParam {String}       tags 属性（可选）
     * @apiParam {String}       begin_enter_time 入驻开始时间（可选）
     * @apiParam {String}       end_enter_time 入驻结束时间（可选）
     * @apiParam {String}       orderType 排序类型（0：入驻时间）可选，默认 0
     * @apiParam {String}       sort 排序（0：逆序，1：顺序）可选，默认 0
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
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * "count": 2,
     * "page_num": 1,
     * "page": "1",
     * "pagesize": 20,
     * "list": [
     * {
     * "school_id": 1,//学生ID
     * "region_id": 1,//地域ID
     * "region_name": "1",
     * "split_class": 1,//分级(0:初中  1：普高 2：中专
     * "split_class_name":'普高',
     * "level_id": 1,学校级别ID 1:一级达标校，2：二级达标校，3：三级达标校，4：四级达标校
     * "level_id_name":"一级达标校",
     * "school_name": "1",//学校名称
     * "pic_url": null,//学校图片
     * "contact_man": null,//联系人
     * "contact_tel": null,//联系电话
     * "contact_address": null,//联系地址
     * "enterflag": 0,//入驻标志：0否 1是
     * "property": 0,//学校属性(0:公立 1：私立）
     * "property_name":'公立',
     * "enter_time": null//入驻时间，
     * "tags": {//属性
     * "1": "区重点",
     * "2": "市重点",
     * "3": "省重点",
     * "4": "重点",
     * "5": "示范校",
     * "6": "示范高中"
     * },
     * },
     * ]
     * }
     * }
     */
    public function getList()
    {
        $param = input('param.');

        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/SchoolManage/getInfo 获取学校信息
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                SchoolManage
     * @apiDescription          获取学校信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       school_id 学校ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "school_id": 1,//学校ID
     * "pic_url": null,//校徽
     * "school_name": "1",//学校名称
     * "level_id":1,//学校级别ID 1:一级达标校，2二级达标校，3：三级达标校，4：四级达标校
     * "split_class": 1,//分级(0:初中  1：普高 2：中专
     * "property": 0,//学校属性(0:公立 1：私立）
     * "contact_address": null,//联系地址
     * "setup_time": null,//学校创办时间
     * "president": null,//现任校长
     * "school_motto": null,//校训
     * "school_intro": null,//学校简介
     * "school_culture": null,//学校文化
     * "enterflag": 0,//入驻标志：0否 1是
     * "enter_time": null//入驻时间
     * "tags"://属性,
     * "region_id": 1161,//地域ID
     * "region_name": "鼓楼",//地域名称
     * "county": 1161,//区ID
     * "city": 1160,//市ID
     * "province": 1159//省ID
     * }
     * }
     */
    public function getInfo()
    {
        $param['school_id'] = input('school_id', '');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/SchoolManage/addInfo 添加或编辑学校
     * @apiVersion 1.0.0
     * @apiName addInfo
     * @apiGroup SchoolManage
     * @apiDescription 添加或编辑学校
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} school_id 学校ID(编辑时填)
     * @apiParam {Int} region_id 地域ID
     * @apiParam {String} region_name 地域名称
     * @apiParam {String} pic_url 校徽
     * @apiParam {String} school_name 学校名称
     * @apiParam {Int} level_id 学校级别ID 1:一级 2二级，3：三级，4四级
     * @apiParam {Int} split_class 分级(0:初中  1：普高 2：中专
     * @apiParam {Int} property 学校性质(0:公立 1：私立）
     * @apiParam {String} contact_address 联系地址
     * @apiParam {Int} setup_time 学校创办时间
     * @apiParam {String} president 现任校长
     * @apiParam {String} school_motto 校训
     * @apiParam {String} school_intro 学校简介
     * @apiParam {String} school_culture 学校文化
     * @apiParam {Int} enterflag 入驻标志：0否 1是
     * @apiParam {Int} enter_time  入驻时间
     * @apiParam {String} tags 属性（多个用","分隔）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/addInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/SchoolManage/delInfo 批量删除学校
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup SchoolManage
     * @apiDescription 批量删除学校
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} school_id 学校ID(批量用","隔开)
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
        $url = $school_api . '/api/SchoolManage/delInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /data/SchoolManage/addUser 创建学校用户
     * @apiVersion 1.0.0
     * @apiName addUser
     * @apiGroup SchoolManage
     * @apiDescription 通过帐号（手机号/邮箱/用户名）和密码登录，成功返回token，错误返回错误信息
     * @apiParam {String} user_name 登录名(必填).
     * @apiParam {String} password 用户密码6-12位字符(必填).
     * @apiParam {String} re_password 再次输入密码(必填).
     * @apiParam {String} ustype 用户类型(必填).
     * @apiParam {String} real_name 真实姓名(必填).
     * @apiParam {String} email 邮箱(选填).
     * @apiParam {Int} college_id 大学ID(选填)默认0
     * @apiParam {Int} high_admin_flag 高中管理员标志(0:否 1：是）(选填)默认0
     * @apiParam {String} auditing_flag 用户审核状态，0:等待审核， 1：审核未通过 ，
     *                    2：审核通过（高校入驻申请必须是0）.
     * @apiParam {Int} is_formal 0 签约用户 1 体验用户
     * @apiParam {Int} formal_time 体验时间
     * @apiParam {Int} scholl_id 学校ID
     * @apiParam {String} duties 在校职务
     * @apiParam {String} auth_pic 认证资料
     * @apiParam {String} reg_type 注册方式，默认手机(mobile),邮箱(email),用户名(username)
     *
     * @apiSuccess {Int} code 错误代码，1是成功,<br>
     * -20011:手机号码格式不正确,<br>
     * -20012:前后两次输入的密码不匹配,<br>
     * -20013:用户名已经注册,<br>
     * -20015:用户名用户类型不能为空注册,<br>
     * -20014:注册失败,<br>
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function addUser()
    {
        $param = input('param.');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/addUse';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/SchoolManage/getSchoolItem 获取学校搜索条件
     * @apiVersion              1.0.0
     * @apiName                 getSchoolItem
     * @apiGROUP                SchoolManage
     * @apiDescription          获取学校搜索条件
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "split_class": [
     * {
     * "id": 0,
     * "name": "初中"
     * },
     * {
     * "id": 1,
     * "name": "普高"
     * },
     * {
     * "id": 2,
     * "name": "中专"
     * }
     * ],
     * "level_id": [
     * {
     * "id": 1,
     * "name": "一级达标"
     * },
     * {
     * "id": 2,
     * "name": "二级达标"
     * },
     * {
     * "id": 3,
     * "name": "三级达标"
     * },
     * {
     * "id": 4,
     * "name": "无"
     * }
     * ],
     * "property": [
     * {
     * "id": 0,
     * "name": "公立"
     * },
     * {
     * "id": 1,
     * "name": "私立"
     * }
     * ],
     * "tags": [
     * {
     * "id": "区重点",
     * "name": "区重点"
     * },
     * {
     * "id": "市重点",
     * "name": "市重点"
     * },
     * {
     * "id": "省重点",
     * "name": "省重点"
     * },
     * {
     * "id": "重点",
     * "name": "重点"
     * },
     * {
     * "id": "示范校",
     * "name": "示范校"
     * },
     * {
     * "id": "示范高中",
     * "name": "示范高中"
     * }
     * ]
     * }
     * }
     */
    public function getSchoolItem()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getSchoolItem';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
