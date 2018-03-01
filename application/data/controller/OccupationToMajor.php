<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class OccupationToMajor extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    /**
     * @api {post} /data/OccupationToMajor/occupationToMajorList 获取职业列表
     * @apiVersion 1.0.0
     * @apiName occupationToMajorList
     * @apiGroup OccupationToMajor
     * @apiDescription 获取职业类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} occupationTypeName 职业名称.（可选）
     * @apiParam {String} occupation_id 职业ID.（可选）
     * @apiParam {String} num 分页数.
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
     *   id:  职业专业的关系ID
     *   occupationName: 职业名称
     *   majorTypeName:专业大类名称
     *   major_id:专业ID
     *   majorNumber: 专业代码
     *   majorName: 相关专业名称
     *   average_salary:平均薪资
     *   employment_direction:就业方向
     * }
     * ]
     * }
     */
    public function occupationToMajorList()
    {
        $occupation_name = input('param.occupationName', '', 'htmlspecialchars');
        $occupation_id = input('param.occupation_id', '', 'htmlspecialchars');
        $num = input('param.num', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/OccupationToMajor/occupationToMajorList';
        $param['num'] = $num;
        $param['page'] = $page;
        $param['occupation_id'] = $occupation_id;
        $param['occupationName'] = $occupation_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/OccupationToMajor/addOccupationToMajor 获取职业列表
     * @apiVersion 1.0.0
     * @apiName addOccupationToMajor
     * @apiGroup OccupationToMajor
     * @apiDescription 获取职业列表
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     * @apiParam {Int}     occupation_id:职业ID
     * @apiParam {String}  occupationNumber: 职业编号
     * @apiParam {String}  occupationName: 职业名称
     * @apiParam {String}  majorTypeName:专业类型名称
     * @apiParam {Int}     major_id:专业ID
     * @apiParam {String}  majorNumber: 专业代码
     * @apiParam {String}  majorName: 专业名称
     * @apiParam {String}  average_salary:平均薪资
     * @apiParam {String}  employment_direction:就业方向
     * @apiParam {String}  job_skill:职业技能
     * @apiParam {String}  work_content:工作内容
     * @apiParam {String}  description:职业描述
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addOccupationToMajor()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/OccupationToMajor/addOccupationToMajor';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/OccupationToMajor/editOccupationToMajor 查看职业信息
     * @apiVersion 1.0.0
     * @apiName editOccupationToMajor
     * @apiGroup OccupationToMajor
     * @apiDescription 查看职业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    id: 职业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 职业ID
     *   occupation_id:职业ID
     *   occupationNumber: 职业编号
     *   occupationName: 职业名称
     *   majorTypeName:专业类型名称
     *   major_id:专业ID
     *   majorNumber: 专业代码
     *   majorName: 专业名称
     *   average_salary:平均薪资
     *   employment_direction:就业方向
     *   job_skill:职业技能
     *   work_content:工作内容
     *   description:职业描述
     * }
     * ]
     * }
     */
    public function editOccupationToMajor()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/OccupationToMajor/editOccupationToMajor';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/OccupationToMajor/saveOccupationToMajor 提交修改职业信息
     * @apiVersion 1.0.0
     * @apiName saveOccupationToMajor
     * @apiGroup OccupationToMajor
     * @apiDescription 提交修改职业信息
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     * @apiParam {Int}     id: 职业ID
     * @apiParam {Int}     occupation_id:职业ID
     * @apiParam {String}  occupationNumber: 职业编号
     * @apiParam {String}  occupationName: 职业名称
     * @apiParam {String}  majorTypeName:专业类型名称
     * @apiParam {Int}     major_id:专业ID
     * @apiParam {String}  majorNumber: 专业代码
     * @apiParam {String}  majorName: 专业名称
     * @apiParam {String}  average_salary:平均薪资
     * @apiParam {String}  employment_direction:就业方向
     * @apiParam {String}  job_skill:职业技能
     * @apiParam {String}  work_content:工作内容
     * @apiParam {String}  description:职业描述
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveOccupationToMajor()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/OccupationToMajor/saveOccupationToMajor';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/OccupationToMajor/deleteOccupationToMajor 删除职业信息
     * @apiVersion 1.0.0
     * @apiName deleteOccupationToMajor
     * @apiGroup OccupationToMajor
     * @apiDescription 删除职业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    id: 职业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteOccupationToMajor()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/OccupationToMajor/deleteOccupationToMajor';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
