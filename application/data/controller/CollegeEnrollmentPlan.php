<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeEnrollmentPlan extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/CollegePlanList 招生计划列表
     * @apiVersion 1.0.0
     * @apiName CollegePlanList
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 招生计划列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} college_id  大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data: {
     * 法学类: [
     * {
     *    id: 主键ID
     *    year: 年份ID,
     *    title: 招生计划,
     * }
     * ]
     * }
     * }
     */
    public function collegePlanList()
    {
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/collegePlanList';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/addYearList 添加招生计划列表
     * @apiVersion 1.0.0
     * @apiName addYearList
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 添加招生计划列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} year  年份.
     * @apiParam {int} college_id  大学ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addYearList()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addYearList';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/deleteYearInfo 删除招生计划
     * @apiVersion 1.0.0
     * @apiName deleteYearInfo
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 删除招生计划
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} year  年份.
     * @apiParam {int} college_id  大学ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteYearInfo()
    {
        $year = input('param.year', '', 'intval');
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/deleteYearInfo';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/checkProvincePlan 判断生成招生计划
     * @apiVersion 1.0.0
     * @apiName checkProvincePlan
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 判断生成招生计划
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} province_id 省份ID.
     * @apiParam {Int} enrollment_year 年份.
     * @apiParam {int} college_id  大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.(2可以添加 1不能添加)
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function checkProvincePlan()
    {
        $year = input('param.enrollment_year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/checkProvincePlan';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeEnrollmentPlan/addEnrollmentPlanList 编辑招生计划列表
     * @apiVersion 1.0.0
     * @apiName addEnrollmentPlanList
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 编辑招生计划列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} province_id 省份ID.
     * @apiParam {Int} enrollment_year 年份.
     * @apiParam {int} college_id 院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data: {
     * 法学类: [
     * {
     *    id: 院校专业ID,
     *    college_id: 大学ID,
     *    needYear: 修学时间,
     *    department_id: 院系ID,
     *    batch: 批次,
     *    science_class: 学科,
     *    majorName: "专业",
     *    majorTypeName: "专业大类",
     *    department_name: "院系"
     * }
     * ]
     * }
     * }
     */
    public function addEnrollmentPlanList()
    {
        $year = input('param.enrollment_year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addEnrollmentPlanList';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeEnrollmentPlan/addOneMajorList 添加专业列表
     * @apiVersion 1.0.0
     * @apiName addOneMajorList
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 添加专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} province_id 省份ID.
     * @apiParam {Int} year 年份.
     * @apiParam {int} college_id 院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
    * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data: { [
     * {
     *    id: 开设专业ID,
     *    major_number: 专业代码,
     *    major_name: 专业名称,
     * }
     * ]
     * }
     * }
     */
    public function addOneMajorList()
    {
        $year = input('param.enrollment_year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addOneMajorList';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/addOneMajorPlan 新增单个专业功能
     * @apiVersion 1.0.0
     * @apiName addOneMajorPlan
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 新增单个专业功能
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} major_id 开设专业ID.
     * @apiParam {Int} year 年份.
     * @apiParam {int} college_id 院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addOneMajorPlan()
    {
        $year = input('param.year');
        $college_id = input('param.college_id', '', 'intval');
        $major_id = input('param.major_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addOneMajorPlan';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['major_id'] = $major_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeEnrollmentPlan/addCollegeMajorInPlan 招生计划新增编辑专业
     * @apiVersion 1.0.0
     * @apiName addCollegeMajorInPlan
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 招生计划新增编辑专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id ID
     * @apiParam {Int} planNumber 招生数
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */

    public function addCollegeMajorInPlan()
    {
        $info = input('param.');
        // $info = [
        //     'id'=>['199'],
        //     'planNumber'=>['100']
        // ];
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addCollegeMajorInPlan';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/deleteCollegePlan 删除招生计专业
     * @apiVersion 1.0.0
     * @apiName deleteCollegePlan
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 删除招生计专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id ID (多个用','分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */

    public function deleteCollegePlan()
    {
        $ids = input('param.id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addCollegeMajorInPlan';
        $param['id'] = $ids;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    /**
     * @api {post} /data/CollegeEnrollmentPlan/planMultiplex 招生计划复用
     * @apiVersion 1.0.0
     * @apiName planMultiplex
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 招生计划复用
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} year 当前年份
     * @apiParam {Int} mult_year 目标年份
     * @apiParam {int} college_id 院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function planMultiplex()
    {
        $ids = input('param.college_id', '', 'intval');
        $ids = input('param.year', '');
        $ids = input('param.mult_year', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/addCollegeMajorInPlan';
        $param['college_id'] = $college_id;
        $param['year'] = $year;
        $param['mult_year'] = $mult_year;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeEnrollmentPlan/excelPlan 导入功能
     * @apiVersion 1.0.0
     * @apiName excelPlan
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 导入功能
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} url   excel url地址.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function excelPlan()
    {
        // $url = 'http://or9vns8l3.bkt.clouddn.com/plan1.xlsx';
        $excel_url = input('param.url');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeEnrollmentPlan/excelPlan';
        $param['excel_url'] = $excel_url;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
