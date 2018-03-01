<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class College extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/college/collegeList 获取院校列表（后端）
     * @apiVersion 1.0.0
     * @apiName collegeList
     * @apiGroup College
     * @apiDescription 获取院校列表（后端）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} title 标题（可选）.
     * @apiParam {String} province 省份（可选）.
     * @apiParam {String} schools_type 学校类型（可选）.
     * @apiParam {String} batch 学历层次（可选）.
     * @apiParam {String} nature 办学性质（可选）.
     * @apiParam {int} pagesize 当前条数.
     * @apiParam {int} page 页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  college_id:大学ID.
     *  title:大学名称.
     *  region_id:市区ID.
     *  province_name:省份.
     *  schools_type:院校类别
     *  collegeCode:院校代码.
     *  collegeNature:办学性质
     *  collegesAndUniversities:院校隶属.
     *  school_tags:院校标签.
     *  school_level:学历层次(1 本一 2本二 3 专科).
     * }
     * ]
     * }
     *
     */
    public function collegeList()
    {
        $title = input('param.title', '', 'htmlspecialchars');
        $province = input('param.province', '', 'htmlspecialchars');
        $schools_type = input('param.schools_type', '', 'htmlspecialchars');
        $school_level = input('param.batch', '', 'htmlspecialchars');
        $collegeNature = input('param.nature', '', 'htmlspecialchars');
        $page = input('param.page', '1', 'intval');
        $pagesize = input('param.pagesize', '', 'intval');
        $college_api = config('college_api');
        $url = $college_api . '/index/College/collegeList';
        $param['title'] = $title;
        $param['province'] = $province;
        $param['school_level'] = $school_level;
        $param['schools_type'] = $schools_type;
        $param['collegeNature'] = $collegeNature;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/college/editCollege 获取院校默认信息
     * @apiVersion 1.0.0
     * @apiName editCollege
     * @apiGroup College
     * @apiDescription 获取院校默认信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} college_id 大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      college_id:大学ID.
     *      token 用户的token.
     *      time 请求的当前时间戳.
     *      sign 签名.
     *      title:大学名称.
     *      region_id:市区ID.
     *      schools_type:院校类别.
     *      collegeCode:院校代码.
     *      collegeNature:办学性质.
     *      collegesAndUniversities:院校隶属.
     *      principal:校长.
     *      teacher_num:教工人数.
     *      master_num:硕士点数.
     *      doctor_num:博士点数.
     *      academician_num:院士人数.
     *      boy_num:男生比例.
     *      girl_num:女生比例.
     *      students_source:生源比例.
     *      school_characteristic:办学特色.
     *      school_motto:校训.
     *      school_profiles:学校简介.
     *      toll_standard:收费标准.
     *      website:官网地址.
     *      school_tags:,院校标签.
     *      address:地址.
     *      telphone:手机号.
     *      email:邮箱.
     *      thumb:院校图标.
     *      school_level:学历层次( 本一 本二 专科).
     * }
     * ]
     * }
     *
     */

    public function editCollege()
    {
        $college_id = input('param.college_id', '', 'intval');
        $college_api = config('college_api');
        $url = $college_api . '/index/College/editCollege';
        $param['college_id'] = $college_id;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/college/saveCollege 更新院校(后端)
     * @apiVersion 1.0.0
     * @apiName saveCollege
     * @apiGroup College
     * @apiDescription 更新院校(后端)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String}title:大学名称.
     * @apiParam {int}region_id:市区ID.
     * @apiParam {String}schools_type:院校类别.
     * @apiParam {String}collegeCode:院校代码.
     * @apiParam {String}collegeNature:办学性质.
     * @apiParam {String}collegesAndUniversities:院校隶属.
     * @apiParam {String}principal:校长.
     * @apiParam {Int}teacher_num:教工人数.
     * @apiParam {int}master_num:硕士点数.
     * @apiParam {int}doctor_num:博士点数.
     * @apiParam {int}academician_num:院士人数.
     * @apiParam {String} boy_num:男生比例.
     * @apiParam {String} girl_num:女生比例.
     * @apiParam {String} students_source:生源比例.
     * @apiParam {String}school_characteristic:办学特色.
     * @apiParam {String}school_motto:校训.
     * @apiParam {String}school_profiles:学校简介.
     * @apiParam {String}toll_standard:收费标准.
     * @apiParam {String}website:官网地址.
     * @apiParam {String}school_tags:,院校标签.
     * @apiParam {String}address:地址.
     * @apiParam {String}telphone:手机号.
     * @apiParam {String}email:邮箱.
     * @apiParam {String}thumb:院校图标.
     * @apiParam {String}school_level:学历层次( 本一 本二 专科).
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollege()
    {

        $college_api = config('college_api');
        $url = $college_api . '/index/College/saveCollege';
        $param = input('param.');
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/college/addCollege 添加院校(后端)
     * @apiVersion 1.0.0
     * @apiName addCollege
     * @apiGroup College
     * @apiDescription 添加院校(后端)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String}title:大学名称.
     * @apiParam {int}region_id:市区ID.
     * @apiParam {String}schools_type:院校类别.
     * @apiParam {String}collegeCode:院校代码.
     * @apiParam {String}collegeNature:办学性质.
     * @apiParam {String}collegesAndUniversities:院校隶属.
     * @apiParam {String}principal:校长.
     * @apiParam {Int}teacher_num:教工人数.
     * @apiParam {int}master_num:硕士点数.
     * @apiParam {int}doctor_num:博士点数.
     * @apiParam {int}academician_num:院士人数.
     * @apiParam {String} boy_num:男生比例.
     * @apiParam {String} girl_num:女生比例.
     * @apiParam {String} students_source:生源比例.
     * @apiParam {String}school_characteristic:办学特色.
     * @apiParam {String}school_motto:校训.
     * @apiParam {String}school_profiles:学校简介.
     * @apiParam {String}toll_standard:收费标准.
     * @apiParam {String}website:官网地址.
     * @apiParam {String}school_tags:,院校标签.
     * @apiParam {String}address:地址.
     * @apiParam {String}telphone:手机号.
     * @apiParam {String}email:邮箱.
     * @apiParam {String}thumb:院校图标.
     * @apiParam {String}school_level:学历层次( 本一 本二 专科).
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollege()
    {
        $college_api = config('college_api');
        $url = $college_api . '/index/College/addCollege';
        $param = input('param.');
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/college/setCollegeStatus 删除院校
     * @apiVersion 1.0.0
     * @apiName setCollegeStatus
     * @apiGroup College
     * @apiDescription 删除院校
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} college_id 大学ID.
     * @apiParam {int} status 状态（0删除 1恢复）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * }
     * ]
     * }
     *
     */

    public function setCollegeStatus()
    {
        $college_id = input('param.college_id', '');
        $status = input('param.status', '0', 'intval');
        $college_api = config('college_api');
        $url = $college_api . '/index/College/setCollegeStatus';
        $param['college_id'] = $college_id;
        $param['status'] = $status;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/college/getSchoolType 获取院校类别
     * @apiVersion 1.0.0
     * @apiName getSchoolType
     * @apiGroup College
     * @apiDescription 获取院校类别
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  type_name:类别名称.
     *  type_id:类别ID'.
     * }
     * ]
     * }
     *
     */
    public function getSchoolType()
    {
        $college_api = config('college_api');
        $url = $college_api . '/index/College/getSchoolType';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get|post} /data/college/addUser 创建院校用户
     * @apiVersion 1.0.0
     * @apiName addUser
     * @apiGroup College
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

}
