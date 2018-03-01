<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeDepartment extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/CollegeDepartment/getDepartmentInfo 获取该大学院系列表
     * @apiVersion 1.0.0
     * @apiName getDepartmentInfo
     * @apiGroup CollegeDepartment
     * @apiDescription 获取省市区列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} college_id  大学ID.
     * @apiParam {int} pagesize  当前条数.
     * @apiParam {Int} page 当前页.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  id: 院系ID,
     *  college_id: 大学ID,
     *  department_name: "院系名称",
     *  create_time: 1497578857
     * }
     * ]
     * }
     *
     */
    public function getDepartmentInfo()
    {
        $college_id = input('param.college_id', '', 'intval');
        $pagesize = input('param.pagesize', '', 'intval');
        $page = input('param.page', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeDepartment/getDepartmentInfo';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $data = curl_api($url, $param, 'get', 0);
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeDepartment/addDepartment 添加院系
     * @apiVersion 1.0.0
     * @apiName addDepartment
     * @apiGroup CollegeDepartment
     * @apiDescription 添加院系
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id 院校ID.
     * @apiParam {String} department_name 院系名称.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addDepartment()
    {
        $info = input('param.');
        $info['create_time'] = time();
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeDepartment/addDepartment';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

     /**
     * @api {post} /data/CollegeDepartment/addDepartment 获取修改院系信息
     * @apiVersion 1.0.0
     * @apiName addDepartment
     * @apiGroup CollegeDepartment
     * @apiDescription 获取修改院系信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id 院系ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editDepartment()
    {
        $id = input("param.id", '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeDepartment/editDepartment';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeDepartment/saveDepartment 提交院系信息
     * @apiVersion 1.0.0
     * @apiName saveDepartment
     * @apiGroup CollegeDepartment
     * @apiDescription 提交院系信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id 院校ID.
     * @apiParam {String} department_name 院系名称.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveDepartment()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeDepartment/editDepartmentSubmit';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeDepartment/deleteDepartment 删除院系
     * @apiVersion 1.0.0
     * @apiName deleteDepartment
     * @apiGroup CollegeDepartment
     * @apiDescription 删除院系
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id 院系ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteDepartment()
    {
        $id = input("param.id", '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeDepartment/deleteDepartment';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
