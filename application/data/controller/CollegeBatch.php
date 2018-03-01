<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeBatch extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/CollegeBatch/getCollegeBatchList 获取批次列表
     * @apiVersion                                         1.0.0
     * @apiName                                            getCollegeBatchList
     * @apiGroup                                           CollegeBatch
     * @apiDescription                                     获取批次列表
     *
     * @apiParam {String}       token 用户的token.
     * @apiParam {String}       time 请求的当前时间戳.
     * @apiParam {String}       sign 签名.
     * @apiParam {String}       college_id 签名.
     * @apiParam {String}       batch 签名.
     * @apiParam {Int}          page 当前页.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  id:          批次ID,
     *  college_id:  大学ID,
     *  batch:       院系名称,
     * }
     * ]
     * }
     *
     */
    public function getCollegeBatchList()
    {
        $param['pagesize'] = input('param.pagesize');
        $param['page'] = input('param.page');
        $param['college_id'] = input('param.college_id');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeBatch/getCollegeBatchList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeBatch/addCollegeBatch  添加批次
     * @apiVersion                                      1.0.0
     * @apiName                                         addCollegeBatch
     * @apiGroup                                        CollegeBatch
     * @apiDescription                                  添加批次
     *
     * @apiParam {String} token         用户的token.
     * @apiParam {String} time          请求的当前时间戳.
     * @apiParam {String} sign          签名.
     * @apiParam {Int}    college_id    院校ID.
     * @apiParam {String} batch         院系.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollegeBatch()
    {
        $param = input('param.');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeBatch/addCollegeBatch';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

     /**
     * @api {post} /data/CollegeBatch/editCollegeBatch 获取院校批次
     * @apiVersion                                           1.0.0
     * @apiName                                              addDepartment
     * @apiGroup                                             CollegeDepartment
     * @apiDescription                                       获取院校批次
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    id                批次ID.
      *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editCollegeBatch()
    {
        $param['id']= input('param.id');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeBatch/editCollegeBatch';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeBatch/saveCollegeBatch 修改批次
     * @apiVersion                                      1.0.0
     * @apiName                                         saveCollegeBatch
     * @apiGroup                                        CollegeBatch
     * @apiDescription                                  修改批次
     *
     * @apiParam {String} token         用户的token.
     * @apiParam {String} time          请求的当前时间戳.
     * @apiParam {String} sign          签名.
     * @apiParam {Int}    college_id    院校ID.
     * @apiParam {String} batch         批次.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegeBatch()
    {
        $param = input('param.');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeBatch/saveCollegeBatch';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeBatch/delCollegeBatch 删除院系
     * @apiVersion 1.0.0
     * @apiName delCollegeBatch
     * @apiGroup CollegeBatch
     * @apiDescription 提交院系信息-5
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id 院系ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delCollegeBatch()
    {
        $param['id']= input('param.id');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeBatch/delCollegeBatch';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
