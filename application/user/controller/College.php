<?php

namespace app\user\controller;

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
     * @api {get|post} /user/college/addUser 创建院校用户
     * @apiVersion 1.0.0
     * @apiName addUser
     * @apiGroup College
     * @apiDescription 通过帐号（手机号/邮箱/用户名）和密码登录，成功返回token，错误返回错误信息
     * @apiParam {String} user_name 登录名(必填).
     * @apiParam {String} password 用户密码6-12位字符(必填).
     * @apiParam {String} re_password 再次输入密码(必填).
     * @apiParam {String} utype 用户类型(必填).
     * @apiParam {String} real_name 真实姓名(必填).
     * @apiParam {String} email 邮箱(选填).
     * @apiParam {Int} college_id 大学ID(选填)默认0
     * @apiParam {Int} high_admin_flag 高中管理员标志(0:否 1：是）(选填)默认0
     * @apiParam {String} auditing_flag 用户审核状态，0:等待审核， 1：审核未通过 ，
     *                    2：审核通过（高校入驻申请必须是0）.
     * @apiParam {Int} is_formal 0 签约用户 1 体验用户
     * @apiParam {Int} formal_time 体验时间
     * @apiParam {Int} school_id 学校ID
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
        $url = $base_api . '/api/user/addUser';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/college/editUser 编辑院校用户
     * @apiVersion 1.0.0
     * @apiName editUser
     * @apiGroup College
     * @apiParam {String} user_id 登录名(必填).
     * @apiParam {String} user_name 登录名.
     * @apiParam {String} password 用户密码6-12位字符.
     * @apiParam {String} utype 用户类型(必填).
     * @apiParam {String} real_name 真实姓名(必填).
     * @apiParam {String} email 邮箱(选填).
     * @apiParam {Int} college_id 大学ID(选填)默认0
     * @apiParam {Int} high_admin_flag 高中管理员标志(0:否 1：是）(选填)默认0
     * @apiParam {String} auditing_flag 用户审核状态，0:等待审核， 1：审核未通过 ，
     *                    2：审核通过（高校入驻申请必须是0）.
     * @apiParam {Int} is_formal 0 签约用户 1 体验用户
     * @apiParam {Int} formal_time 体验时间
     * @apiParam {Int} school_id 学校ID
     * @apiParam {String} duties 在校职务
     * @apiParam {String} auth_pic 认证资料
     *
     * @apiSuccess {Int} code 错误代码，1是成功,<br>
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function editUser()
    {
        $param = input('param.');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/adminUpdateUserData';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/college/getUserInfo 院校用户信息
     * @apiVersion 1.0.0
     * @apiName getUserInfo
     * @apiGroup College
     * @apiDescription 院校用户信息
     *
     * @apiParam {Int}       user_id 用户ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 院校用户信息.
     * @apiSuccessExample  {json} Success-Response:
     *{
     * "code": 1,
     * "msg": "获取成功",
     * "data": {
     * "user_id": 4851,
     * "user_name": "13105977770",//用户账号
     * "is_active": 0,
     * "title": "北京大学",//院校名称
     * "province": "北京",
     * "city": "北京市西城区",
     * "region_id": 8,
     * "region_name": "福建,福州,鼓楼区"
     * }
     * }
     */
    public function getUserInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/CollegeManage/getUserInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/college/getCollegeByRegion 市区获取院校
     * @apiVersion 1.0.0
     * @apiName getCollegeByRegion
     * @apiGroup College
     * @apiDescription 市区获取院校
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} city 城市 如（福州）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "操作成功",
     * "data": [
     * {
     * "college_id": 523,
     * "title": "福建师范大学"
     * },
     * ]
     * }
     */
    public function getCollegeByRegion()
    {
        $param = input('param.');
        $college_api = config('college_api');
        $url = $college_api . '/index/college/getCollegeByRegion';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/college/collegeRegisterschList 后台大学注册审核列表
     * @apiVersion 1.0.0
     * @apiName collegeRegisterschList
     * @apiGroup College
     * @apiDescription 后台大学注册审核列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} college_name 院校.
     * @apiParam {int} is_approval .审核标志(0:等待审核 1：审核通过 2：审核未通过）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data:  [
     * {
     *    request_id: 注册ID,
     *    college_name: "院校名称",
     *    college_code: "院校代码"
     *    addtime: 添加时间,
     *    name: "联系人",
     *    telephone: "联系电话",
     *    region_name: "院校省市",
     *    address: '地址',
     *    telephone: "手机号",
     *    is_approval: 审核标志(0:等待审核 1：审核通过 2：审核未通过）
     * }
     * ]
     * }
     */
    public function collegeRegisterschList()
    {
        $param['page'] = input('param.page', '1', 'intval');
        $param['college_name'] = input('param.college_name', '', 'htmlspecialchars');
        $param['is_approval'] = input('param.is_approval', '-1');
        $college_api = config('college_api');
        $url = $college_api . '/index/College/collegeRegisterschList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }



    /**
     * @api {post} /user/college/getCollegeRegisterschInfo  高校入驻信息查询
     * @apiVersion 1.0.0
     * @apiName getCollegeRegisterschInfo
     * @apiGroup College
     * @apiDescription 通过用户ID查询匹配
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    request_id 院校审核数据ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *     user_id        用户ID
     *     college_name   申请入驻的高校名称
     *     colllege_code  申请入驻的高校代码
     *     region_id      院校所在的地区ID
     *     region_name    地区名称
     *     city_id        城市ID
     *     city_name      福州市
     *     province_id    省份ID
     *     province_name  省份名称
     *     address        院校详细地址
     *     name           申请人姓名
     *     duty           申请人职务
     *     telephone      申请人手机号码
     *     email          申请人邮件地址
     *     QQ             申请QQ号码
     *     front_id_card  身份证正面
     *     after_id_card  身份证反面
     *     hold_id_card   手持身份证
     *     upload_id      申请入驻附件的ID
     * ]
     * }
     *
     */
    public function getCollegeRegisterschInfo()
    {
        $param['request_id'] = input('param.request_id', '', 'intval');
        $college_api = config('college_api');
        $url = $college_api . '/index/College/getCollegeRegisterschInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/college/verifyRegistersch 提交入驻审核通过
     * @apiVersion 1.0.0
     * @apiName verifyRegistersch
     * @apiGroup College
     * @apiDescription 提交入驻审核通过
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} request_id 大学注册ID.
     * @apiParam {int} is_approval 审核标志(1：审核通过 2：审核未通过）.
     * @apiParam {int} reason      审核愿原因
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function verifyRegistersch()
    {
        $param['is_approval'] = input('param.is_approval', 0, 'intval');
        $param['reason'] = input('param.reason', '', 'htmlspecialchars');
        $param['request_id'] = input('param.request_id', '', 'intval');

        $college_api = config('college_api');
        $url = $college_api . '/index/College/verifyRegistersch';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
