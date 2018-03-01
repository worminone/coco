<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Occupation extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    /**
     * @api {post} /data/Occupation/occupationTypeList 获取职业类型列表
     * @apiVersion 1.0.0
     * @apiName occupationTypeList
     * @apiGroup Occupation
     * @apiDescription 获取职业类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} occupationTypeName 职业名称.（可选）
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
     *   type_id: 职业类型ID
     *   occupationTypeNumber:职业类型代码
     *   occupationTypeName: 职业类型名称
     *   industry_id: 行业ID
     *   by_occupation_names：相关职业
     *   pic_url:图片地址
     *   industry_name:行业名称
     * }
     * ]
     * }
     */
    public function occupationTypeList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $occupation_type_name = input('param.occupationTypeName', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/occupationTypeList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['occupationTypeName'] = $occupation_type_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/Occupation/addOccupationType 新增职业类型
     * @apiVersion 1.0.0
     * @apiName addOccupationType
     * @apiGroup Occupation
     * @apiDescription 新增职业类型
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} occupationTypeNumber:职业类型代码
     * @apiParam {String} occupationTypeName: 职业类型名称
     * @apiParam {String} industry_id: 行业ID
     * @apiParam {String} pic_url:图片地址
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addOccupationType()
    {

        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/addOccupationType';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Occupation/editOccupationType 查看职业类型
     * @apiVersion 1.0.0
     * @apiName editOccupationType
     * @apiGroup Occupation
     * @apiDescription 查看职业类型
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    type_id: 职业类型ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   type_id: 职业类型ID
     *   occupationTypeNumber:职业类型代码
     *   occupationTypeName: 职业类型名称
     *   industry_id: 行业ID
     *   pic_url:图片地址
     *   industry_name:行业名称
     * }
     * ]
     * }
     */
    public function editOccupationType()
    {
        $type_id = input('param.type_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/editOccupationType';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Occupation/saveOccupationType 提交修改职业类型
     * @apiVersion 1.0.0
     * @apiName saveOccupationType
     * @apiGroup Occupation
     * @apiDescription 提交修改职业类型
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    type_id: 职业类型ID
     * @apiParam {String} occupationTypeNumber:职业类型代码
     * @apiParam {String} occupationTypeName: 职业类型名称
     * @apiParam {String} industry_id: 行业ID
     * @apiParam {String} pic_url:图片地址
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveOccupationType()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/saveOccupationType';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Occupation/deleteOccupationType 删除职业类型
     * @apiVersion 1.0.0
     * @apiName deleteOccupationType
     * @apiGroup Occupation
     * @apiDescription 删除职业类型
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    type_id: 职业类型ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteOccupationType()
    {
        $type_id = input('param.type_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/deleteOccupationType';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Occupation/occupationList 获取职业信息
     * @apiVersion 1.0.0
     * @apiName occupationList
     * @apiGroup Occupation
     * @apiDescription 获取职业类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} occupation_name 职业名称.（可选）
     * @apiParam {String} occupation_id 职业ID.（可选）
     * @apiParam {String} type_id 职业类型ID.（可选）
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
     *   occupation_id:  职业专业的关系ID
     *   type_id:        职业类型id
     *   occupation_name: 职业名称
     *   occupation_describe: 职业描述
     *   job_content:工作内容
     *   access_by_major:相关专业
     *   employment_forward: 就业方向
     *   avg_graduation: 平均薪酬
     *   industry_name:所属行业
     *   occupation_type_name:职业类型
     * }
     * ]
     * }
     */
    public function occupationList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $type_id = input('param.type_id', '', 'intval');
        $occupation_name = input('param.occupation_name', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/occupationList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['type_id'] = $type_id;
        $param['occupation_name'] = $occupation_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Occupation/addOccupation 新增职业信息
     * @apiVersion 1.0.0
     * @apiName addOccupation
     * @apiGroup Occupation
     * @apiDescription 获取职业列表
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     *
     * @apiParam {String}  type_id: 职业编号
     * @apiParam {Int}     occupation_name:职业名称
     * @apiParam {String}  occupation_describe: 职业描述
     * @apiParam {String}  job_content: 专业名称
     * @apiParam {String}  avg_graduation:平均薪资
     * @apiParam {String}  skill_approach:就业方向
     * @apiParam {String}  access_by_major:相关专业
     * @apiParam {String}  job_content:工作内容
     * @apiParam {String}  occupation_describe:职业描述
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addOccupation()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/addOccupation';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Occupation/editOccupation 查看职业信息
     * @apiVersion 1.0.0
     * @apiName editOccupation
     * @apiGroup Occupation
     * @apiDescription 查看职业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    occupation_id: 职业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      type_id: 职业编号
     *      occupation_name:职业名称
     *      occupation_describe: 职业描述
     *      job_content: 专业名称
     *      avg_graduation:平均薪资
     *      skill_approach:就业方向
     *      access_by_major:相关专业
     *      job_content:工作内容
     *      occupation_describe:职业描述
     * }
     * ]
     * }
     */
    public function editOccupation()
    {
        $occupation_id = input('param.occupation_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/editOccupation';
        $param['occupation_id'] = $occupation_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Occupation/saveOccupation 提交修改职业信息
     * @apiVersion 1.0.0
     * @apiName saveOccupation
     * @apiGroup Occupation
     * @apiDescription 获取职业列表
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     *
     * @apiParam {Int}     occupation_id: 职业ID
     * @apiParam {String}  type_id: 职业编号
     * @apiParam {Int}     occupation_name:职业名称
     * @apiParam {String}  occupation_describe: 职业描述
     * @apiParam {String}  job_content: 专业名称
     * @apiParam {String}  avg_graduation:平均薪资
     * @apiParam {String}  skill_approach:就业方向
     * @apiParam {String}  access_by_major:相关专业
     * @apiParam {String}  job_content:工作内容
     * @apiParam {String}  occupation_describe:职业描述
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveOccupation()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/saveOccupation';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Occupation/deleteOccupation 删除职业信息
     * @apiVersion 1.0.0
     * @apiName deleteOccupation
     * @apiGroup Occupation
     * @apiDescription 删除职业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    occupation_id: 职业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteOccupation()
    {
        $occupation_id = input('param.occupation_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/deleteOccupation';
        $param['occupation_id'] = $occupation_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
