<?php

namespace app\data\controller;

use think\Db;
use app\common\controller\Admin;

class CollegeScore extends Admin
{

    /**
     * @api {post} /data/CollegeScore/collegeYearList 获取年份列表
     * @apiVersion 1.0.0
     * @apiName     collegeYearList
     * @apiGroup    CollegeScore
     * @apiDescription 获取年份列表
     *
     * @apiParam {String}           token 用户的token.
     * @apiParam {String}           time 请求的当前时间戳.
     * @apiParam {String}           sign 签名.
     * @apiParam {Int}              college_id 签名.
     * @apiParam {Int}              status  状态（1 招生计划 2录取分数 3 院校分数）.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data: [
     * {
     *    id:     '主键ID'
     *    year:   '年份ID',
     *    title:  '名称',
     * }
     * ]
     * }
     */
    public function collegeYearList()
    {
        $param['college_id'] = input('param.college_id');
        $param['page'] = input('param.page');
        $param['pagesize'] = input('param.pagesize');
        $param['status'] = input('param.status');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeScore/CollegeYearList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/addYear 添加年份列表
     * @apiVersion 1.0.0
     * @apiName addYear
     * @apiGroup CollegeScore
     * @apiDescription 添加招生计划列表
     *
     * @apiParam {String}       token 用户的token.
     * @apiParam {String}       time 请求的当前时间戳.
     * @apiParam {String}       sign 签名.
     * @apiParam {int}          year  年份.
     * @apiParam {int}          status  类型（1 招生计划 2录取分数 3 院校分数）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addYear()
    {
        $param['college_id'] = input('param.college_id');
        $param['status'] = input('param.status');
        $param['year'] = input('param.year');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeScore/addYear';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/CollegeScore/deleteYear 删除年份信息
     * @apiVersion 1.0.0
     * @apiName deleteYear
     * @apiGroup CollegeScore
     * @apiDescription 删除招生计划
     *
     * @apiParam {String}       token 用户的token.
     * @apiParam {String}       time 请求的当前时间戳.
     * @apiParam {String}       sign 签名.
     * @apiParam {int}          id 年份ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteYear()
    {
        $param['id'] = input('param.id');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeScore/deleteYear';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/copyYear 复用院校分数
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
        $url =  $college_api.'/index/CollegeScore/copyYear';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/getCollegeScoreList 获取院校分数列表
     * @apiVersion                                      1.0.0
     * @apiName                                         getCollegeScoreList
     * @apiGroup                                        CollegeScore
     * @apiDescription                                  获取院校分数列表
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
     *      province: 省份名称,
     *      department_id: 学院ID,
     *      department_name: 学院名称",
     *      province: "省份",
     *      science: "学科",
     *      enrollmentYear: "年份",
     *      enrollmentBatch: "批次",
     *      max: "最高分",
     *      min: "最低分",
     *      avg: "平均分",
     *      shengkong: "省控线",
     * }
     * ]
     * }
     */
    public function getCollegeScoreList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $page = input('param.page', '1', 'int');
        $department_id = input('param.department_id', '');
        $province_id = input('param.province_id', '');
        $science = input('param.science');
        $enrollmentYear = input('param.enrollmentYear', '', 'intval');
        $enrollmentBatch = input('param.batch');
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/getCollegeScoreList';
        $param['department_id'] = $department_id;
        $param['province_id'] = $province_id;
        $param['science'] = $science;
        $param['enrollmentYear'] = $enrollmentYear;
        $param['enrollmentBatch'] = $enrollmentBatch;
        $param['college_id'] = $college_id;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/addCollegeScore 新增院校分数线
     * @apiVersion                                      1.0.0
     * @apiName                                         addCollegeScore
     * @apiGroup                                        CollegeScore
     * @apiDescription                                  新增院校分数线
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {String} department_id     院系ID.
     * @apiParam {String} province_id       省份ID.
     * @apiParam {String} science           学科类型.(文理类)
     * @apiParam {String} subject           学科类型.(政史类)
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} enrollmentBatch   批次.
     * @apiParam {Int} year                 年份.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollegeScore()
    {

        $param = input('param.');
        $department_id = input('param.department_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/addCollegeScore';
        $param['enrollmentBatch'] = $param['batch'];
        $param['year'] = $param['enrollmentYear'];
        $param['department_id'] = $department_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/CollegeScore/editCollegeScore   获取院校分数线
     * @apiVersion                                      1.0.0
     * @apiName                                         editCollegeScore
     * @apiGroup                                        CollegeScore
     * @apiDescription                                  获取院校分数线
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    id                院校分数id.
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
     *      province: 省份名称,
     *      department_id: 学院ID,
     *      department_name: 学院名称",
     *      province_id: "省份ID",
     *      science: "学科",
     *      enrollmentYear: "年份",
     *      enrollmentBatch: "批次",
     *      max: "最高分",
     *      min: "最低分",
     *      avg: "平均分",
     *      shengkong: "省控线",
     * }
     * ]
     * }
     */
    public function editCollegeScore()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/editCollegeScore';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/saveCollegeScore 提交修改院校分数线
     * @apiVersion                                      1.0.0
     * @apiName                                         saveCollegeScore
     * @apiGroup                                        CollegeScore
     * @apiDescription                                  提交修改院校分数线
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} id                   院系分数ID.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {String} department_id     院系ID.
     * @apiParam {String} province_id       省份ID.
     * @apiParam {String} science           学科类型.(名称)
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} enrollmentBatch   批次.
     * @apiParam {Int} year                 年份.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegeScore()
    {
        $param = input('param.');
        $param['department_id'] = input('param.department_id', '0');
        $param['enrollmentBatch'] = $param['batch'];
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/saveCollegeScore';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/deleteCollegeScore 删除院校分数线
     * @apiVersion                                      1.0.0
     * @apiName                                         deleteCollegeScore
     * @apiGroup                                        CollegeScore
     * @apiDescription                                  删除院校分数线
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} id                   院系分数ID(多个','分割).
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteCollegeScore()
    {
        $id = input('param.id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/deleteCollegeScore';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeScore/excelScore 院校分数导入功能
     * @apiVersion 1.0.0
     * @apiName excelScore
     * @apiGroup CollegeScore
     * @apiDescription 院校分数导入功能
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
    public function excelScore()
    {
        $excel_url = input('param.url', '');
        $college_id = input('param.college_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeScore/excelScore';
        $param['url'] = $excel_url;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
