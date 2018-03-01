<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Major extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    /**
     * @api {post} /data/Major/MajorTopList 学科门类列表
     * @apiVersion 1.0.0
     * @apiName MajorTopList
     * @apiGroup Major
     * @apiDescription 学科门类列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorTypeName 专业类别名称.（可选）
     * @apiParam {String} level 等级（1 门类 2类型）
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
     *   type_id: 学科主键ID
     *   majorTypeNumber:学科代码
     *   majorTypeName:学科名称
     *   majorTopTypeNumber：类型上一级代码
     *   educationType 学历类型(B 本科 C:专科）
     * }
     * ]
     * }
     */
    public function majorTopList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $major_type_name = input('param.majorTypeName', '', 'htmlspecialchars');
        $education_type = input('param.educationType', '', 'htmlspecialchars');
        $level = input('param.level', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorTopList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['level'] = $level;
        $param['majorTypeName'] = $major_type_name;
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/addMajorTop 添加学科门类
     * @apiVersion 1.0.0
     * @apiName addMajorTop
     * @apiGroup Major
     * @apiDescription 添加学科门类
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     * @apiParam {String}  majorTypeNumber:学科代码
     * @apiParam {String}  majorTypeName: 学科名称
     * @apiParam {String}  majorTopTypeNumber：类型上一级代码(如果为顶级这个值不用传)
     * @apiParam {String}  educationType:学历类型(B 本科 C:专科)
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addMajorTop()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/addMajorTop';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    /**
     * @api {post} /data/Major/editMajorTop 获取学科门类信息
     * @apiVersion 1.0.0
     * @apiName editMajorTop
     * @apiGroup Major
     * @apiDescription 获取学科门类信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} type_id: 门类ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
          * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   type_id: 学科主键ID
     *   majorTypeNumber:学科代码
     *   majorTypeName:学科名称
     *   majorTopTypeNumber：类型上一级代码
     *   educationType 学历类型(B 本科 C:专科）
     * }
     * ]
     * }
     */
    public function editMajorTop()
    {
        $type_id = input('param.type_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/editMajorTop';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/saveMajorTop 提交修改学科门类
     * @apiVersion 1.0.0
     * @apiName saveMajorTop
     * @apiGroup Major
     * @apiDescription 提交修改学科门类
     *
     * @apiParam {String}  token 用户的token.
     * @apiParam {String}  time 请求的当前时间戳.
     * @apiParam {String}  sign 签名.
     * @apiParam {Int}  type_id: 学科主键ID
     * @apiParam {String}  majorTypeNumber:学科代码
     * @apiParam {String}  majorTypeName: 学科名称
     * @apiParam {String}  majorTopTypeNumber：类型上一级代码(如果为顶级这个值不用传)
     * @apiParam {String}  educationType:学历类型(B 本科 C:专科)
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveMajorTop()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/saveMajorTop';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/deleteMajorTop 删除学科门类
     * @apiVersion 1.0.0
     * @apiName deleteMajorTop
     * @apiGroup Major
     * @apiDescription 删除学科门类
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} type_id: 门类ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteMajorTop()
    {
        $type_id = input('param.type_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/deleteMajorTop';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/MajorTypeList 学科类型列表
     * @apiVersion 1.0.0
     * @apiName MajorTypeList
     * @apiGroup Major
     * @apiDescription 学科类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorTypeName 专业类别名称.
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
     *   type_id: 学科主键ID
     *   majorTypeNumber:学科代码
     *   majorTypeName:学科名称
     *   majorTopTypeNumber：类型上一级代码
     *   educationType 学历类型(B 本科 C:专科）
     * }
     * ]
     * }
     */
    public function majorTypeList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $major_type_name = input('param.majorTypeName', '', 'htmlspecialchars');
        $education_type = input('param.educationType', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorTypeList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['majorTypeName'] = $major_type_name;
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/MajorList 专业列表
     * @apiVersion 1.0.0
     * @apiName MajorList
     * @apiGroup Major
     * @apiDescription 专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorName 专业名称.
     * @apiParam {String} majorTypeNumber 专业类型编码.
     * @apiParam {String} needYear 年限
     * @apiParam {String} grantDegree 学位
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
     *   major_id: 专业ID
     *   type_id:专业类型ID
     *   majorNumber:专业代码
     *   majorTypeNumber：专业类型编码
     *   majorTypeName：专业类型名称
     *   majorTopNumber：专业门类编码
     *   majorTopName：专业门类名称
     *   majorName 专业名称
     *   degreeType: 学历类型本/专
     *   grantDegree:授予学位
     *   needYear:修业年限
     * }
     * ]
     * }
     */
    public function majorList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $major_name = input('param.majorName', '', 'htmlspecialchars');
        $major_type_number = input('param.majorTypeNumber', '', 'htmlspecialchars');
        $need_year = input('param.needYear', '不限', 'htmlspecialchars');
        $grant_degree = input('param.grantDegree', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['majorTypeNumber'] = $major_type_number;
        $param['majorName'] = $major_name;
        $param['needYear'] = $need_year;
        $param['grantDegree'] = $grant_degree;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Major/addMajor 新增专业
     * @apiVersion 1.0.0
     * @apiName addMajor
     * @apiGroup Major
     * @apiDescription 新增专业
     *
     * @apiParam {String}   token 用户的token.
     * @apiParam {String}   time 请求的当前时间戳.
     * @apiParam {String}   sign 签名.
     * @apiParam {String}   majorNumber:专业代码
     * @apiParam {String}   majorTypeNumber：专业类型编码
     * @apiParam {String}   pic_url： 封面图片
     * @apiParam {String}   video_url：视频地址
     * @apiParam {String}   majorName 专业名称
     * @apiParam {String}   degreeType: 学历类型本/专
     * @apiParam {String}   grantDegree:授予学位
     * @apiParam {String}   needYear:修业年限
     * @apiParam {String}   professionalIntroduction：专业简介
     * @apiParam {String}   industryDistribution:行业分布
     * @apiParam {String}   fiveYearsOfGraduation:毕业5年薪
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addMajor()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/addMajor';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/editMajor 获取专业信息
     * @apiVersion 1.0.0
     * @apiName editMajor
     * @apiGroup Major
     * @apiDescription 获取专业信息
     *
     * @apiParam {String}   token 用户的token.
     * @apiParam {String}   time 请求的当前时间戳.
     * @apiParam {String}   sign 签名.
     * @apiParam {String}   major_id: 专业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   major_id: 专业ID
     *   type_id:专业类型ID
     *   majorNumber:专业代码
     *   majorTypeNumber：专业类型编码
     *   majorName 专业名称
     *   degreeType: 学历类型本/专
     *   grantDegree:授予学位
     *   needYear:修业年限
     *   industryDistribution:行业分布
     *   fiveYearsOfGraduation:毕业5年薪
     *   majorTypeNumber: 专业门类编码
     * }
     * ]
     * }
     */
    public function editMajor()
    {
        $major_id = input('param.major_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/editMajor';
        $param['major_id'] = $major_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/saveMajor 提交修改专业信息
     * @apiVersion 1.0.0
     * @apiName saveMajor
     * @apiGroup Major
     * @apiDescription 提交修改专业信息
     *
     * @apiParam {String}   token 用户的token.
     * @apiParam {String}   time 请求的当前时间戳.
     * @apiParam {String}   sign 签名.
     * @apiParam {String}   majorNumber:专业代码
     * @apiParam {String}   majorTypeNumber：专业类型编码
     * @apiParam {String}   majorName 专业名称
     * @apiParam {String}   degreeType: 学历类型本/专
     * @apiParam {String}   grantDegree:授予学位
     * @apiParam {String}   needYear:修业年限
     * @apiParam {String}   pic_url： 封面图片
     * @apiParam {String}   video_url：视频地址
     * @apiParam {String}   industryDistribution:行业分布
     * @apiParam {String}   fiveYearsOfGraduation:毕业5年薪
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveMajor()
    {
        $info = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/saveMajor';
        $param = $info;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/deleteMajor 删除专业信息
     * @apiVersion 1.0.0
     * @apiName deleteMajor
     * @apiGroup Major
     * @apiDescription 删除专业信息
     *
     * @apiParam {String}   token 用户的token.
     * @apiParam {String}   time 请求的当前时间戳.
     * @apiParam {String}   sign 签名.
     * @apiParam {String}   major_id: 专业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteMajor()
    {
        $major_id = input('param.major_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/deleteMajor';
        $param['major_id'] = $major_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Major/collegeListByNumber 院校专业
     * @apiVersion 1.0.0
     * @apiName collegeListByNumber
     * @apiGroup Major
     * @apiDescription 院校专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorNumber 专业代码.
     * @apiParam {String} title 大学标题.（可选）
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
     *   id:  大学专业ID,
     *   college_id: 大学ID,
     *   majorNumber: 专业代码,
     *   majorName: 专业名称,
     *   majorTypeNumber: 专业类别代码,
     *   majorTypeName: 专业类别名称,
     *   subject: 科目,
     *   title: 大学名称,
     *   school_level: 学历层次(1 本一 2本二 3 专科),
     *   province: 省份,
     *   school_tags: 院校标签,
     *   schools_type: 院校类别,
     *   collegeNature: 办学性质
     *   collegesAndUniversities：隶属
     * }
     * ]
     * }
     */
    public function collegeListByNumber()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $major_number = input('param.majorNumber', '', 'htmlspecialchars');
        $title = input('param.title', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/collegeListByNumber';
        $param['num'] = $pagesize;
        $param['title'] = $title;
        $param['majorNumber'] = $major_number;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Major/getBaseMajor 科目和科类列表
     * @apiVersion 1.0.0
     * @apiName getBaseMajor
     * @apiGroup Major
     * @apiDescription 科目和科类列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   suject: 科目
     *   science: 文理学科,
     *   school_level: 学校层次
     *   need_year: 修学年限
     * ]
     * }
     *
     */
    public function getBaseMajor(){
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/getBaseMajor';
        $data = curl_api($url, [], 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Major/subjectList 根据科内获取大学专业信息
     * @apiVersion 1.0.0
     * @apiName subjectList
     * @apiGroup CollegeAdmin
     * @apiDescription 根据科内获取大学专业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    pagesize 分页数.
     * @apiParam {String} title 大学名称 
     * @apiParam {String} province 省份 
     * @apiParam {String} schools_type 院校类别 
     * @apiParam {String} batch 批次 
     * @apiParam {String} subject 学科 
     * @apiParam {String} type 大学或者专业(大学1 专业2) 
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id :院校专业Id
     *   majorTypeNumber: 专业代码,
     *   majorTypeName: 专业名称
     *   id: 大学专业id,
     *   college_id: 大学ID,
     *   subject: 学科,
     *   majorNumber: 专业代码,
     *   majorName: 专业名称,
     *   batch: 批次,
     *   needYear: 学年,
     *   majorTypeNumber: 专业类型代码,
     *   majorTypeName: 专业类型名称,
     *   title: 大写名称,
     *   school_tags: 院系标签,
     *   schools_type: 院校类型,
     *   collegeCode: 院校代码    
     * ]
     * }
     *
     */
    public function subjectList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $title = input('param.title', '', 'htmlspecialchars');
        $province = input('param.province', '', 'htmlspecialchars');
        $schools_type = input('param.schools_type', '', 'htmlspecialchars');
        $batch = input('param.batch');
        $subject = input('param.subject');
        $type = input('param.type', '','intval');

        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/subjectList';

        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['title'] = $title;
        $param['province'] = $province;
        $param['schools_type'] = $schools_type;
        $param['batch'] = $batch;
        $param['subject'] = $subject;
        $param['type'] = $type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

}
