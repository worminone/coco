<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeMajor extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    
    /**
     * @api {get} /data/CollegeMajor/getTypeNumberlist 专业类型列表
     * @apiVersion 1.0.0
     * @apiName getTypeNumberlist
     * @apiGroup CollegeMajor
     * @apiDescription 专业类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} page 当前页数.
     * @apiParam {String} title 标题.
     * @apiParam {String} major_type_number 专业类型代码.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    type_id: 专业类型ID
     *    majorTypeNumber:专业类型代码
     *    majorTypeName:专业类型名称
     * }
     * ]
     * }
     *
     */
    public function getTypeNumberlist()
    {
        $major_type_number = input('param.major_type_number', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/getTypeNumberlist';
        $param['major_type_number'] = $major_type_number;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {get} /data/CollegeMajor/getCollegeDepartment 院系列表
     * @apiVersion 1.0.0
     * @apiName getCollegeDepartment
     * @apiGroup CollegeMajor
     * @apiDescription 院系列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id 大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getCollegeDepartment()
    {
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/getCollegeDepartment';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/CollegeMajor/getMajorTypeInfo
     * 学科门类, 学科类别,专业名称层级关系(下拉列表)
     * @apiVersion 1.0.0
     * @apiName getMajorTypeInfo
     * @apiGroup CollegeMajor
     * @apiDescription 学科门类, 学科类别,专业名称层级关系(下拉列表)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} type_name 类别代码.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    majorTypeNumber:专业类型代码
     *    majorTypeName:专业类型名称
     * }
     * ]
     * }
     *
     */
    public function getMajorTypeInfo()
    {
        $type_name = input('param.type_name', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/getMajorTypeInfo';
        $param['type_name'] = $type_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/CollegeMajor/collegeMajorList 开设专业列表
     * @apiVersion 1.0.0
     * @apiName getCollegeMajor
     * @apiGroup CollegeMajor
     * @apiDescription 开设专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} page 当前页数.
     * @apiParam {Int} pagesize 分页数.
     * @apiParam {String} college_id 大学ID.（必选）
     * @apiParam {String} title 专业名.
     * @apiParam {String} is_enable  是否启用 1 启用 2 未启用.
     * @apiParam {String} major_type_number 专业类型编码.
     * @apiParam {Int} department_id 院系ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id: 专业ID
     *    department_id:院系ID
     *    department_name:院系名称
     *    collegeName:院校名称
     *    collegeCode:院校代码
     *    majorName:专业名称
     *    majorTypeName:专业类型名称
     *    is_enable：是否启用（1是 2否）
     * }
     * ]
     * }
     *
     */
    public function collegeMajorList()
    {
        $param['page'] = input('param.page', '10', 'intval');
        $param['pagesize'] = input('param.pagesize', '10', 'intval');
        $param['college_id'] = input('param.college_id', '1', 'intval');
        $param['title'] = input('param.title', '', 'htmlspecialchars');
        $param['department_id'] = input('param.department', '-1', 'intval');
        $param['is_enable'] = input('param.is_enable', '', 'intval');
        $param['major_type_number'] = input('param.major_type_number', '', 'htmlspecialchars');

        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/collegeMajorList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

   
    /**
     * @api {post} /data/CollegeMajor/addCollegeMajor 添加院校专业
     * @apiVersion 1.0.0
     * @apiName addRedisCollegeMajor
     * @apiGroup CollegeMajor
     * @apiDescription 添加院校专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id: 院校ID
     * @apiParam {Int} department_id:院系ID
     * @apiParam {String} majorNumber:专业代码
     * @apiParam {String} needYear:修业年限
     * @apiParam {String} batch:批次
     * @apiParam {String} science_class:招生科类
     *（1文史, 2理工,3 艺术（文）,4 艺术（理）5,体育（文）6,体育（理））
     * @apiParam {String} subject:选择科目
     *(1不限 2历史 3地理 4生物 5化学 6政治 7物理 8技术(浙江))
     * @apiParam {String} professional_certificate:专业证书
     * @apiParam {String} academic_degree:专业授予学位
     * @apiParam {String} fiveYearsOfGraduation:毕业5年月薪
     * @apiParam {String} training_course:修学课程
     * @apiParam {String} intro:简介
     * @apiParam {String} importantFlag:重点学科标志(0:否 1：是）
     * @apiParam {Int} is_hot:是否热门(0:否 1：是）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollegeMajor()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/addCollegeMajor';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/CollegeMajor/editCollegeMajor 专业编辑信息
     * @apiVersion 1.0.0
     * @apiName editCollegeMajor
     * @apiGroup CollegeMajor
     * @apiDescription 专业编辑信息-6
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id:专业id
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
     *      major_id: 专业ID,
     *      college_id: 大学ID,
     *      department_id: 院系ID,
     *      collegeName: "大学名称",
     *      collegeCode: "院校代码",
     *      majorNumber: "专业代码",
     *      majorName: "专业名称",
     *      majorTypeNumber: "专业类型代码",
     *      majorTypeName: "专业类型名称",
     *      batch: "批次",
     *      special: "",
     *      science_class: "招生科类",
     *      subject: "选择科目",
     *      professional_certificate: "专业证书",
     *      academic_degree: "专业授予学位",
     *      fiveYearsOfGraduation: 毕业5年月薪,
     *      training_course: "修学课程",
     *      intro: "简介",
     *      importantFlag: 重点学科标志(0:否 1：是）,
     *      department_name: "院系名称",
     *      major_top_number: "专业门类代码",
     *      major_top_type: "专业门类名称,
     *      educationType: "学历层次"
     *  }
     * ]
     * }
     *
     */
    public function editCollegeMajor()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/editCollegeMajor';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajor/saveRedisCollegeMajor 提交编辑专业信息
     * @apiVersion 1.0.0
     * @apiName saveRedisCollegeMajor
     * @apiGroup CollegeMajor
     * @apiDescription 提交编辑专业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id: 专业ID
     * @apiParam {Int} college_id: 院校ID
     * @apiParam {Int} department_id:院系ID
     * @apiParam {String} majorNumber:专业代码
     * @apiParam {String} needYear:修业年限
     * @apiParam {String} batch:批次
     * @apiParam {String} science_class:招生科类
     *（1文史, 2理工,3 艺术（文）,4 艺术（理）5,体育（文）6,体育（理））
     * @apiParam {String} subject:选择科目(1不限 2历史 3地理 4生物 5化学 6政治 7物理 8技术(浙江))
     * @apiParam {String} professional_certificate:专业证书
     * @apiParam {String} academic_degree:专业授予学位
     * @apiParam {String} fiveYearsOfGraduation:毕业5年月薪
     * @apiParam {String} training_course:修学课程
     * @apiParam {String} intro:简介
     * @apiParam {String} importantFlag:重点学科标志(0:否 1：是）
     * @apiParam {Int} is_hot:是否热门(0:否 1：是）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegeMajor()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/saveCollegeMajor';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    
    /**
     * @api {get} /data/CollegeMajor/deleteCollegeMajor 删除专业
     * @apiVersion 1.0.0
     * @apiName deleteCollegeMajor
     * @apiGroup CollegeMajor
     * @apiDescription 删除专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id:专业id (多个用‘,’分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteCollegeMajor()
    {
        $id = input('param.id', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/deleteRedisCollegeMajor';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/CollegeMajor/getHotMajorList 热门专业列表
     * @apiVersion 1.0.0
     * @apiName getHotMajorList
     * @apiGroup CollegeMajor
     * @apiDescription 热门专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {Int}   college_id  院校ID.
     * @apiParam {String} sign 签名.
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
     *  majorTypeNumber: 专业类型
     *  majorTypeName: 专业类型
     *  majorNumber: 专业代码
     *  majorName: 专业名称
     *  science_class: 专业选考科目
     * }
     * ]
     * }
     *
     */

    public function getHotMajorList()
    {
        $param['college_id'] = input('param.college_id', '');
        $param['page'] = input('param.page', '1', 'intval');
        $param['pagesize'] = input('param.pagesize', '10', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/getHotMajorList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajor/setEnable 设置启用专业
     * @apiVersion 1.0.0
     * @apiName setEnable
     * @apiGroup CollegeMajor
     * @apiDescription 设置专业是否启用
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id 专业ID.
     * @apiParam {Int} is_enable 设置启用（1启用，2停用）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     *
     */

    public function setEnable()
    {
        $id = input('param.id', '');
        $is_enable = input('param.is_enable');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/setEnable';
        $param['id'] = $id;
        $param['is_enable'] = $is_enable;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeMajor/excelMajor 开设专业导入功能
     * @apiVersion 1.0.0
     * @apiName excelMajor
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
    public function excelMajor()
    {
        $excel_url = input('param.url', '');
        $college_id = input('param.college_id', '');

        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/excelMajorV2';
        $param['url'] = $excel_url;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

     /**
     * @api {get} /data/CollegeMajor/addHotCollegeMajor 新增和删除热门
     * @apiVersion                                       1.0.0
     * @apiName                                          addHotCollegeMajor
     * @apiGroup                                         CollegeMajor
     * @apiDescription                                   专业编辑信息
     *
     * @apiParam {String} token     用户的token.
     * @apiParam {String} time      请求的当前时间戳.
     * @apiParam {String} sign      签名.
     * @apiParam {Int} id           院校专业id
     * @apiParam {Int} is_delete:   是否删除(可选， 删除is_delete=1)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addHotCollegeMajor()
    {
        $id = input('param.id');
        $is_delete = input('param.is_delete', '');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/addHotCollegeMajor';
        $param['id'] = $id;
        $param['is_delete'] = $is_delete;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/CollegeMajor/saveCollegeMajorSort 修改热门专业排序
     * @apiVersion                                          1.0.0
     * @apiName                                             saveCollegeMajorSort
     * @apiGroup                                            CollegeMajor
     * @apiDescription                                      修改热门专业排序
     *
     * @apiParam {String} token         用户的token.
     * @apiParam {String} time          请求的当前时间戳.
     * @apiParam {String} sign          签名.
     * @apiParam {Int} id               院校专业id
     * @apiParam {Int} sort             排序
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegeMajorSort()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeMajor/saveCollegeMajorSort';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
