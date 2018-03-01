<?php

namespace app\data\controller;

use think\Db;
use app\common\controller\Admin;

class CollegePlan extends Admin
{
    /**
     * @api {post} /data/CollegePlan/getCollegeMajorBatch 获取院校专业批次
     * @apiVersion                                      1.0.0
     * @apiName                                         getCollegeMajorBatch
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  获取院校专业批次
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} college_id           院校ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  batch: 批次
     * }
     * ]
     * }
     * id 获取是从专业的下拉列表里面最后获取专业的时候 得到的专业ID
     */
    public function getCollegeMajorBatch()
    {

        $id = input('param.id', '', 'intval');
        $status= input('param.status', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/getCollegeMajorBatch';
        $param['id'] = $id;
        $param['status'] = $status;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/getCollegePlanList 获取院校招生计划列表
     * @apiVersion                                      1.0.0
     * @apiName                                         getCollegePlanList
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  获取院校招生计划列表
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {Int} department_id        院系ID.
     * @apiParam {Int} province_id          省份ID.
     * @apiParam {String} science           学科ID.
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} batch             批次.
     * @apiParam {Int} pagesize             分页数量.
     * @apiParam {Int} page                 当前页数.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: id,
     *      college_id: 大学ID,
     *      province_id: 省份ID,
     *      department_id: 学院ID,
     *      department_name: 学院名称",
     *      province: "省份",
     *      science: "学科",
     *      enrollmentYear: "年份",
     *      batch: "批次",
     *      majorNumber: "专业代码",
     *      majorName: "专业名称",
     *      majorTypeNumber: "专业类型代码",
     *      majorTypeName: "专业类型名称",
     *      planNumber: 计划数量,
     * }
     * ]
     * }
     */
    public function getCollegePlanList()
    {

        $department_id = input('param.department_id', '');
        $province_id = input('param.province_id', '');
        $science = input('param.science');
        $enrollmentYear = input('param.enrollmentYear', '', 'intval');
        $batch = input('param.batch');
        $college_id = input('param.college_id', '', 'intval');
        $pagesize = input('param.pagesize', '10', 'int');
        $page = input('param.page', '1', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/getCollegePlanList';
        $param['department_id'] = $department_id;
        $param['province_id'] = $province_id;
        $param['science'] = $science;
        $param['enrollmentYear'] = $enrollmentYear;
        $param['batch'] = $batch;
        $param['college_id'] = $college_id;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }



    /**
     * @api {post} /data/CollegePlan/addCollegePlan 新增院校招生计划
     * @apiVersion                                      1.0.0
     * @apiName                                         addCollegePlan
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  新增院校招生计划
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {String} department_id     院系ID.
     * @apiParam {String} province_id       省份ID.
     * @apiParam {String} science           学科类型.(文理类)
     * @apiParam {String} subject           学科类型.(政史类)
     * @apiParam {String} majorNumber       专业代码
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} batch             批次.
     * @apiParam {Int} year                 年份.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollegePlan()
    {
        $info = input('param.');
        $department_id = input('param.department_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/addCollegePlan';
        $param = $info;
        $param['year'] = $info['enrollmentYear'];
        $param['department_id'] = $department_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/editCollegePlan   获取院校招生计划信息
     * @apiVersion                                      1.0.0
     * @apiName                                         editCollegePlan
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  获取院校招生计划信息
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    id                院校专业id.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: id,
     *      college_id: 大学ID,
     *      province_id: 省份ID,
     *      department_id: 学院ID,
     *      department_name: 学院名称",
     *      province: "省份",
     *      science: "学科",
     *      enrollmentYear: "年份",
     *      batch: "批次",
     *      majorNumber: "专业代码",
     *      majorName: "专业名称",
     *      majorTypeNumber: "专业类型代码",
     *      majorTypeName: "专业类型名称",
     *      major_top_number: '门类代码',
     *      planNumber: 计划数量,
     * }
     * ]
     * }
     */
    public function editCollegePlan()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/editCollegePlan';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/CollegePlan/getScienceYearStatus 获取该年份省市的 学科类型
     * @apiVersion                                      1.0.0
     * @apiName                                         getScienceYearStatus
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  获取该年份省市的
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    year              年份.
     * @apiParam {Int}    province_id       省份ID.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      status: 类型（1 文史 2政史）,
     * }
     * ]
     * }
     */
    public function getScienceYearStatus()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/getScienceYearStatus';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/getBatchList 获取该院校批次列表
     * @apiVersion                                      1.0.0
     * @apiName                                         getBatchList
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  获取该院校批次列表
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    college_id        院校ID.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getBatchList()
    {
        $param['college_id'] = input('param.college_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/getBatchList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /index/CollegePlan/delBatch 删除批次
     * @apiVersion                                      1.0.0
     * @apiName                                         delBatch
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  删除批次
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    id                批次ID.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delBatch()
    {
        $param['id'] = input('param.id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegePlan/delBatch';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegePlan/saveCollegePlan 提交修改院校招生计划
     * @apiVersion                                      1.0.0
     * @apiName                                         saveCollegePlan
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  提交修改院校招生计划
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} id                   计划ID.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {String} department_id     院系ID.
     * @apiParam {String} province_id       省份ID.
     * @apiParam {String} science           学科类型.(名称)
     * @apiParam {String} majorNumber       专业代码
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} batch             批次.
     * @apiParam {Int} year                 年份.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegePlan()
    {
        $param = input('param.');
        $param['department_id'] = input('param.department_id', '0');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/saveCollegePlan';
        $param['admin_key'] = $admin_key;
        $param['year'] = $param['enrollmentYear'];
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/deleteCollegePlan 删除院校招生计划
     * @apiVersion                                      1.0.0
     * @apiName                                         deleteCollegePlan
     * @apiGroup                                        CollegePlan
     * @apiDescription                                  删除院校招生计划
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} id                   计划ID(多个','分割).
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteCollegePlan()
    {
        $param['id'] = input('param.id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/deleteCollegePlan';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/copyYear 复用
     * @apiVersion 1.0.0
     * @apiName copyYear
     * @apiGroup CollegeScore
     * @apiDescription 复用院校分数
     *
     * @apiParam {String}       token 用户的token.
     * @apiParam {String}       time 请求的当前时间戳.
     * @apiParam {String}       sign 签名.
     * @apiParam {int}          college_id  院校ID.
     * @apiParam {int}          year  年份.
     * @apiParam {int}          copy_year  复制到年份.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function copyYear()
    {
        $param = [];
        $param['college_id'] = input('param.college_id');
        $param['year'] = input('param.year');
        $param['copy_year'] = input('param.copy_year');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegePlan/copyYear';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePlan/excelPlan 招生计划导入功能
     * @apiVersion 1.0.0
     * @apiName excelPlan
     * @apiGroup CollegePlan
     * @apiDescription 招生计划导入功能
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    url   excel地址.
     * @apiParam {Int}    college_id 大学id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function excelPlan()
    {
        $excel_url = input('param.url', '');
        $college_id = input('param.college_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePlan/excelPlan';
        $param['url'] = $excel_url;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
