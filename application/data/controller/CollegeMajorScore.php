<?php

namespace app\data\controller;

use app\common\controller\Admin;

class CollegeMajorScore extends Admin
{
    /**
     * @api {post} /data/CollegeMajorScore/getMajorScoreList 获取院校分数列表
     * @apiVersion                                      1.0.0
     * @apiName                                         getMajorScoreList
     * @apiGroup                                        CollegeMajorScore
     * @apiDescription                                  获取院校分数列表
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {Int} department_id        院系ID.
     * @apiParam {Int} province_id          省份ID.
     * @apiParam {String} science           学科ID.
     * @apiParam {Int} year                 年份.
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
     *      year: "年份",
     *      batch: "批次",
     *      max: "最高分",
     *      min: "最低分",
     *      avg: "平均分",
     *      shengkong: "省控线",
     * }
     * ]
     * }
     */
    public function getMajorScoreList()
    {
        $college_id = input('param.college_id', '');
        $province_id = input('param.province_id', '');
        $department_id = input('param.department_id', '');
        $year = input('param.year', '');
        $science = input('param.science', '');
        $batch = input('param.batch', '');
        $title = input('param.title', '', 'htmlspecialchars');
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '10', 'int');

        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/getMajorScoreList';

        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['department_id'] = $department_id;
        $param['year'] = $year;
        $param['science'] = $science;
        $param['batch'] = $batch;
        $param['title'] = $title;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/CollegeMajorScore/addMajorScore 新增院校专业信息
     * @apiVersion                                      1.0.0
     * @apiName                                         addMajorScore
     * @apiGroup                                        CollegeMajorScore
     * @apiDescription                                  新增院校专业信息
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
     * @apiParam {Int}    enrollmentYear    年份.
     * @apiParam {String} batch             批次.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addMajorScore()
    {
        $info = input('param.');
        $department_id = input('param.department_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/addMajorScore';
        $param = $info;
        $param['department_id'] = $department_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajorScore/editMajorScore   获取院校专业分数
     * @apiVersion                                      1.0.0
     * @apiName                                         editMajorScore
     * @apiGroup                                        CollegeMajorScore
     * @apiDescription                                  获取院校专业分数
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int}    score_id          分数id.
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
     *      batch: "批次",
     *      majorNumber: "专业代码",
     *      majorName: "专业名称",
     *      majorTypeNumber: "专业类型代码",
     *      majorTypeName: "专业类型名称",
     *      major_top_number: '门类代码',
     *      max: "最高分",
     *      min: "最低分",
     *      avg: "平均分",
     *      shengkong: "省控线",
     * }
     * ]
     * }
     */
    public function editMajorScore()
    {
        $id = input('param.score_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/editMajorScore';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajorScore/saveMajorScore 提交修改院校招生计划
     * @apiVersion                                      1.0.0
     * @apiName                                         saveMajorScore
     * @apiGroup                                        CollegeMajorScore
     * @apiDescription                                  提交修改院校招生计划
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {Int} score_id             专业分数ID.
     * @apiParam {Int} college_id           院校ID.
     * @apiParam {String} department_id     院系ID.
     * @apiParam {String} province_id       省份ID.
     * @apiParam {String} science           学科类型.(名称)
     * @apiParam {Int} enrollmentYear       年份.
     * @apiParam {String} batch             批次.
     * @apiParam {Int} year                 年份.
     * @apiParam {Int} max                  最大值.
     * @apiParam {Int} mim                  最小值.
     * @apiParam {Int} avg                  平均值.
     * @apiParam {Int} shengkong            省控线.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveMajorScore()
    {
        $info = input('param.');
        $department_id = input('param.department_id', '0');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/saveMajorScore';
        $param = $info;
        $param['department_id'] = $department_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/CollegeMajorScore/deleteMajorScore 删除院校招生计划
     * @apiVersion                                      1.0.0
     * @apiName                                         deleteMajorScore
     * @apiGroup                                        CollegeMajorScore
     * @apiDescription                                  删除院校招生计划
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
    public function deleteMajorScore()
    {

        $id = input('param.id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/deleteMajorScore';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajorScore/copyYear 复用专业分数
     * @apiVersion 1.0.0
     * @apiName copyYear
     * @apiGroup CollegeMajorScore
     * @apiDescription 复用专业分数
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
        $url =  $college_api.'/index/CollegeMajorScore/copyYear';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajorScore/excelMajorScore 开设专业导入功能
     * @apiVersion 1.0.0
     * @apiName excelMajorScore
     * @apiGroup CollegeMajor
     * @apiDescription 开设专业导入功能
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
    public function excelMajorScore()
    {
        $excel_url = input('param.url', '');
        $college_id = input('param.college_id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajorScore/excelMajorScore';
        $param['url'] = $excel_url;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
