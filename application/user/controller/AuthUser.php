<?php
/*
 * “百元计划”里老师和学生的授权激活，老师授权后才能登录，学生授权后才能使用生涯教育的相关服务（没有授权的学生APP能登录，学生web端也不能登录），外部调用
 * */
namespace app\user\controller;

use app\common\controller\Admin;
use think\Request;


class AuthUser extends Admin
{
    protected $schoolId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /user/auth_user/updateAuth 用户授权激活
     * @apiVersion 1.0.0
     * @apiName updateAuth
     * @apiGroup User
     * @apiDescription 批量的或者单个的用户授权激活，既可以从大道运营总后台执行，也可以在学校的教务端执行
     *
     * @apiParam {String} time 请求的当前时间戳(原生APP端才需要).
     * @apiParam {String} sign 签名(原生APP端才需要).
     *
     *
     * @apiParam {String}   token 登录名(用户/手机/邮箱)
     * @apiParam {Int}      school_id  学校ID
     * @apiParam {String}   user_ids 单个用户ID，或者多个用户ID用逗号隔开
     * @apiParam {Int}      auth_type  1:学生授权,2:老师授权
     * @apiParam {Int}      auth 授权类型（1：授权激活，0：取消授权）,教务端只能授权激活，不能取消授权

     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function updateAuth()
    {
        $paraArr = input('post.');
        $paraArr['token'] = Request::instance()->header('token');
        $api = config('school_api') . '/api/auth_user/updateAuth';

        $data = curl_api($api, $paraArr, 'post');

        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /**
     * @api {get} /user/auth_user/getAuthCount 学校老师和学生授权数量统计查询
     * @apiVersion 1.0.0
     * @apiName getAuthCount
     * @apiGroup User
     * @apiDescription  大道运营总后台和教务端获取获取某个学校的已授权的老师或者学生的人数
     *
     * @apiParam {String}   time 请求的当前时间戳(原生APP端才需要).
     * @apiParam {String}   sign 签名(原生APP端才需要).
     *
     *
     * @apiParam {String}   token 用户token
     * @apiParam {Int}      school_id 学校ID
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function getAuthCount()
    {

        $paraArr = input('get.');
        $paraArr['token'] = Request::instance()->header('token');
        $api = config('school_api') . '/api/auth_user/getAuthCount';
        $data = curl_api($api, $paraArr, 'get', 0);
//         aa($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }


    /**
     * @api {get} /user/auth_user/getAuthDetail 用户授权数量查询
     * @apiVersion 1.0.0
     * @apiName getAuthDetail
     * @apiGroup User
     * @apiDescription  大道运营总后台和教务端获取获取某个学校的已授权的老师或者学生的人数
     *
     * @apiParam {String}   time 请求的当前时间戳(原生APP端才需要).
     * @apiParam {String}   sign 签名(原生APP端才需要).
     *
     *
     * @apiParam {String}   token 用户token
     * @apiParam {Int}      auth_type  1:查询学生授权数量(默认),2:查询老师授权数量
     * @apiParam {Int}      school_id 学校ID
     * @apiParam {Int}      section 年段ID（查询学生时候有效）
     * @apiParam {Int}      class_num 班级号（查询学生时候有效）
     * @apiParam {Int}      subject_id 科目ID（查询老师时候有效）
     * @apiParam {Int}      section 授权类型（1：授权激活，3：取消授权）
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function getAuthDetail()
    {
        $paraArr = input('get.');
        $paraArr['token'] = Request::instance()->header('token');
        $api = config('school_api') . '/api/auth_user/getAuthDetail';

        $data = curl_api($api, $paraArr, 'get', 0);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

}
