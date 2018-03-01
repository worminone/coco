<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeAdmissionScore extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    /**
     * @api {post} /data/CollegeAdmissionScore/checkProvinceScore 判断生成录取分数
     * @apiVersion 1.0.0
     * @apiName checkProvinceScore
     * @apiGroup CollegeAdmissionScore
     * @apiDescription 判断生成录取分数
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
     */
    public function checkProvinceScore()
    {
        $year = input('param.enrollment_year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/checkProvinceScore';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeAdmissionScore/addCollegeAdmissionScoreList 录取分数数据信息
     * @apiVersion 1.0.0
     * @apiName addCollegeAdmissionScoreList
     * @apiGroup CollegeAdmissionScore
     * @apiDescription 录取分数数据信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} province_id 省份ID.
     * @apiParam {Int} enrollment_year 年份.
     * @apiParam {Int} college_id 大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */

    public function addCollegeAdmissionScoreList()
    {
        $year = input('param.enrollment_year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/addCollegeAdmissionScoreList';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeAdmissionScore/addCollegeAdmissionScore 录取分数新增编辑
     * @apiVersion 1.0.0
     * @apiName addCollegeAdmissionScore
     * @apiGroup CollegeAdmissionScore
     * @apiDescription 招录取分数新增编辑
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id ID
     * @apiParam {Int} province_id 省份ID
     * @apiParam {Int} numberOfAdmissions 招生实际人数
     * @apiParam {int} max 最高分
     * @apiParam {int} min 最低分
     * @apiParam {int} avg 平均分
     * @apiParam {int} sid 分数表id
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     *
     *  $info = [
     *      'province_id'=>'1',                  //省份ID
     *      'info'=>[
     *          'arr1'=>[
     *              '0'=>  "55",                  //录取分数ID
     *              '1'=>  "200",                 //招生实际人数
     *              '2'=>['650','555','444','147'], //最高分 最低分 平均分 分数ID
     *              '3'=>['700','555','444','148'],
     *              '4'=>['650','555','444','149'],
     *
     *          ],
     *      ],
     *  ];
     *
     */
    public function addCollegeAdmissionScore()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/addCollegeAdmissionScore';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeAdmissionScore/addOneMajorScoreList 添加专业列表
     * @apiVersion 1.0.0
     * @apiName addOneMajorScoreList
     * @apiGroup CollegeAdmissionScore
     * @apiDescription 添加专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} province_id 省份ID.
     * @apiParam {int} college_id 院校ID
     * @apiParam {Int} year 年份.
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
    public function addOneMajorScoreList()
    {
        $year = input('param.year');
        $college_id = input('param.college_id', '', 'intval');
        $province_id = input('param.province_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/addOneMajorScoreList';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }



    /**
     * @api {post} /data/CollegeAdmissionScore/addOneMajorScore 添加录取分数单个专业功能
     * @apiVersion 1.0.0
     * @apiName addOneMajorScore
     * @apiGroup CollegeAdmissionScore
     * @apiDescription 添加录取分数单个专业功能
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
    public function addOneMajorScore()
    {
        $year = input('param.year');
        $college_id = input('param.college_id', '', 'intval');
        $major_id = input('param.major_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/addOneMajorScore';
        $param['year'] = $year;
        $param['college_id'] = $college_id;
        $param['major_id'] = $major_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeAdmissionScore/deleteCollegeScore 删除录取分数专业
     * @apiVersion 1.0.0
     * @apiName deleteCollegeScore
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 删除录取分数专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id ID (多个用','分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */

    public function deleteCollegeScore()
    {
        $id = input('param.id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/deleteCollegeScore';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    /**
     * @api {post} /data/CollegeAdmissionScore/excelScore 导入分数功能
     * @apiVersion 1.0.0
     * @apiName excelScore
     * @apiGroup CollegeEnrollmentPlan
     * @apiDescription 导入分数功能
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} url   excel地址.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */

    public function excelScore()
    {
        // $url = 'www.college.com/static/file/score.xlsx';
        $url = input('param.url');
        $excel_url = input('param.url');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmissionScore/excelScore';
        $param['excel_url'] = $excel_url;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
