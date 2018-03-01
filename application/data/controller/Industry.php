<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Industry extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    /**
     * @api {post} /data/Industry/industryList 获取行业列表
     * @apiVersion 1.0.0
     * @apiName industryList
     * @apiGroup Industry
     * @apiDescription 获取行业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} industry_name 行业名称.（可选）
     * @apiParam {String} pagesize 分页数.
     * @apiParam {String} page 当前页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
          * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   industry_id: 行业ID
     *   industry_name:行业名称
     * }
     * ]
     * }
     */
    public function industryList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $industry_name = input('param.industry_name', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Industry/industryList';
        $param['page'] = $page;
        $param['num'] = $pagesize;
        $param['industry_name'] = $industry_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Industry/addIndustry 新增行业信息
     * @apiVersion 1.0.0
     * @apiName addIndustry
     * @apiGroup Industry
     * @apiDescription 新增行业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} industry_name 行业名称.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     */
    public function addIndustry()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Industry/addIndustry';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Industry/editIndustry 编辑查看行业信息
     * @apiVersion 1.0.0
     * @apiName editIndustry
     * @apiGroup Industry
     * @apiDescription 编辑查看行业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} industry_id 行业id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   industry_id: 行业ID
     *   industry_name:行业名称
     * }
     * ]
     * }
     */
    public function editIndustry()
    {
        $industry_id = input('param.industry_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Industry/editIndustry';
        $param['industry_id'] = $industry_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Industry/saveIndustry 提交修改行业信息
     * @apiVersion 1.0.0
     * @apiName saveIndustry
     * @apiGroup Industry
     * @apiDescription 提交修改行业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} industry_id 行业id.
     * @apiParam {String} industry_name 行业名称.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     */
    public function saveIndustry()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Industry/saveIndustry';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Industry/deleteIndustry 删除行业信息
     * @apiVersion 1.0.0
     * @apiName deleteIndustry
     * @apiGroup Industry
     * @apiDescription 删除行业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} industry_id 行业id.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     */
    public function deleteIndustry()
    {
        $industry_id = input('param.industry_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Industry/deleteIndustry';
        $param['industry_id'] = $industry_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
