<?php
namespace app\api\controller\aio;

use think\Db;
use app\common\controller\Api;
use app\article\model\Video;

class AioCollege extends Api
{
    /**
 * @api {post} /api/aio.AioCollege/getCollegeList 获取院校列表(aio)
 * @apiVersion 1.0.0
 * @apiName getCollegeList
 * @apiGroup College
 * @apiDescription 获取院校列表
 *
 * @apiParam {String} token 用户的token.
 * @apiParam {String} time 请求的当前时间戳.
 * @apiParam {String} sign 签名.
 * @apiParam {string} province 省份名称.
 * @apiParam {string} schools_type 类型.
 * @apiParam {string} school_tags 特色.
 * @apiParam {string} title 院校名称.
 * @apiParam {string} page 当前页数.
 * @apiParam {string} pagesize 分页数.
 *
 * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
 * @apiSuccess {String} msg 成功的信息和失败的具体信息.
 * @apiSuccessExample  {json} Success-Response:
 * {
 * "code": 1,
 * "msg": "获取成功",
 * "data": [
 * {
 *  college_id:大学ID.
 *  title: 大学名称.
 *  thumb: 校徽图片
 *  province: 省份
 *  schools_type:院校类别.
 *  school_tags:院校标签.
 * }
 * ]
 * }
 */
    //$url = 'http://www.zgxyzx.net/download/aio/search/init.json';
    public function getCollegeList()
    {
        $province     = input('param.province', '全国', 'htmlspecialchars');
        $schools_type = input('param.schools_type', '不限', 'htmlspecialchars');
        $school_tags  = input('param.school_tags', '不限', 'htmlspecialchars');
        $title  = input('param.title', '', 'htmlspecialchars');
        $pagesize    = input('param.pagesize', '9', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeList';
        $param['province'] = $province;
        $param['schools_type'] = $schools_type;
        $param['school_tags'] = $school_tags;
        $param['title'] = $title;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }
    /**
     * @api {post} /api/aio.AioCollege/getCollegeListV41 获取院校列表V41(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeListV41
     * @apiGroup College
     * @apiDescription 获取院校列表V41
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} province 省份名称.
     * @apiParam {string} schools_type 类型.
     * @apiParam {string} school_tags 特色.
     * @apiParam {string} title 院校名称.
     * @apiParam {string} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     *      "count": 1,
     *      "page_num": 1,
     *      "list": [{
     *          "college_id": 392,
     *          "title": "厦门理工学院",
     *          "thumb": "http://www.1zy.me/uploadfile/badge/small/20160324102032229.jpg",
     *          "province": "福建",
     *          "schools_type": "艺术类",
     *          "school_tags": "",
     *          "academician_num": 0,
     *          "master_num": 0,
     *          "doctor_num": 0,
     *          "import_num": 0,
     *          "import_subject": 61
     *          }]
     *      }
     * }
     */
    public function getCollegeListV41()
    {
        $province     = input('param.province', '全国', 'htmlspecialchars');
        $schools_type = input('param.schools_type', '不限', 'htmlspecialchars');
        $school_tags  = input('param.school_tags', '不限', 'htmlspecialchars');
        $title  = input('param.title', '', 'htmlspecialchars');
        $pagesize    = input('param.pagesize', '9', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeList';
        $param['province'] = $province;
        $param['schools_type'] = $schools_type;
        $param['school_tags'] = $school_tags;
        $param['title'] = $title;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/getCollegeInfo 院校信息(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeInfo
     * @apiGroup College
     * @apiDescription 院校信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    college_id  院校ID
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  college_id:大学ID.
     *  title:大学名称.
     *  region_id:市区ID.
     *  schools_type:院校类别.
     *  collegeCode:院校代码.
     *  collegeNature:办学性质（公立 ,私立）.
     *  collegesAndUniversities:院校隶属.
     *  principal：校长
     *  teacher_num:教工人数.
     *  master_num:硕士点数.
     *  doctor_num:博士点数.
     *  academician_num:院士人数.
     *  boy_num:男生人数.
     *  girl_num:女生人数.
     *  school_characteristic:办学特色.
     *  school_motto:校训.
     *  school_profiles:学校简介.
     *  toll_standard:收费标准.
     *  website:官网地址.
     *  school_tags:,院校标签.
     *  school_level:学历层次(本一 本二  专科).
     * }
     * ]
     * }
     *
     */
    public function getCollegeInfo()
    {
        $college_id = input('param.college_id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeInfo';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $v_param['key_id'] = 'college_id';
        $v_param['id'] = $college_id;
        $v_param['table'] = 'College';
        $v_url =  $college_api.'/index/CollegeAdmin/addBrowView';
        curl_api($v_url, $v_param, 'post');
        $data = curl_api($url, $param, 'post');
//        $data['data']['boy_num'] = intval($data['data']['boy_num']);
//        $data['data']['girl_num'] = intval($data['data']['girl_num']);
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/getAlumnusList 校友数据列表(aio)
     * @apiVersion 1.0.0
     * @apiName getAlumnusList
     * @apiGroup College
     * @apiDescription 校友数据列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    college_id  院校ID
     * @apiParam {string} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id: 校友ID,
     *    name: 校友名字,
     *    img: 头像地址,
     *    occupation_name: 职业名称,
     *    major_name: 专业名称,
     * }
     * ]
     * }
     *
     */
    public function getAlumnusList()
    {
        $college_id = input('param.college_id', '', 'int');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getAlumnusList';
        $param['college_id'] = $college_id;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {get} /api/aio.AioCollege/getAlumnusInfo  校友数据信息(aio)
     * @apiVersion 1.0.0
     * @apiName getAlumnusInfo
     * @apiGroup College
     * @apiDescription 校友数据信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    id   校友id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id:校友id
     *   name:校友姓名
     *   img :校友图片
     *   occupation_name:职业
     *   college_id:当前大学ID
     *   graduation_college:毕业院校
     *   major_name:专业名字
     *   sex:性别
     *   nationality:籍贯
     *   birthday:生日
     *   synopsis:人物简介
     *   experience:人物经历
     *   achievement:主要成就
     * }
     * ]
     * }
     *
     */
    public function getAlumnusInfo()
    {
        $id = input('param.id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getAlumnusInfo';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getTeachersList 师资数据列表(aio)
     * @apiVersion 1.0.0
     * @apiName getTeachersList
     * @apiGroup College
     * @apiDescription 师资数据列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     * @apiParam {Int} college_id:院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id : 师资ID
     *    name:老师姓名
     *    img:图片路径
     *    position:职位
     *    degree:学位
     *    major_name:专业
     * }
     * ]
     * }
     *
     */
    public function getTeachersList()
    {
        $college_id = input('param.college_id', '', 'int');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getTeachersList';
        $param['college_id'] = $college_id;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/getTeachersInfo 师资信息(aio)
     * @apiVersion 1.0.0
     * @apiName getTeachersInfo
     * @apiGroup College
     * @apiDescription 师资信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id:师资id
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id : 老师ID
     *    name:老师姓名
     *    sex:性别
     *    img:图片路径
     *    college_id  院校ID
     *    position:职位
     *    degree:学位
     *    major_id:授课专业ID
     *    brief_introduction:人物简介
     *    achievement:主要成就
     * }
     * ]
     * }
     *
     */
    public function getTeachersInfo()
    {
        $id = input('param.id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getTeachersInfo';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getCollegePicList 校园风采列表(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegePicList
     * @apiGroup College
     * @apiDescription 校园风采列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     * @apiParam {Int} college_id:院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    pic_id : 图片ID
     *    pic_url: 图片地址
     * }
     * ]
     * }
     *
     */
    public function getCollegePicList()
    {
        $college_id = input('param.college_id', '', 'int');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegePicList';
        $param['college_id'] = $college_id;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getCollegePicInfo 校园风采信息(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegePicInfo
     * @apiGroup College
     * @apiDescription 校园风采信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {Int} pic_id:图片ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    pic_id : 图片ID
     *    pic_url: 图片地址
     *    title: 图片标题
     * }
     * ]
     * }
     *
     */
    public function getCollegePicInfo()
    {
        $id = input('param.pic_id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getTeachersInfo';
        $param['pic_id'] = $pic_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/getCollegeVideoList 视频列表(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeVideoList
     * @apiGroup College
     * @apiDescription 视频列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     * @apiParam {Int} college_id:院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    video_id : 视频ID
     *    title: 视频标题
     *    vu: 视频源
     * }
     * ]
     * }
     *
     */
    public function getCollegeVideoList()
    {
        $college_id = input('param.college_id', '', 'int');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeVideoList';
        $param['college_id'] = $college_id;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getCollegeVideoInfo 视频信息(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeVideoInfo
     * @apiGroup College
     * @apiDescription 视频信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {Int} video_id:视频ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    video_id : 视频ID
     *    vu: 视频源
     * }
     * ]
     * }
     *
     */
    public function getCollegeVideoInfo()
    {
        $video_id = input('param.video_id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeVideoInfo';
        $param['video_id'] = $video_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getAioCollegeMajorList 开设专业列表(aio)
     * @apiVersion 1.0.0
     * @apiName getAioCollegeMajorList
     * @apiGroup College
     * @apiDescription 开设专业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     * @apiParam {Int} college_id:院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: 专业ID
     *      majorNumber:专业代码
     *      majorName:专业名称
     *      majorTypeNumber:专业类型代码
     *      majorTypeName:专业类型名称
     *      major_top_number:专业大类代码
     *      major_top_type:专业大类名称
     *      subject:选择科目
     *      department_name：院系名称
     * }
     * ]
     * }
     *
     */
    public function getAioCollegeMajorList()
    {
        $college_id = input('param.college_id', '', 'int');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page    = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getAioCollegeMajorList';
        $param['college_id'] = $college_id;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/getCollegeMajorInfo 专业信息(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeMajorInfo
     * @apiGroup College
     * @apiDescription 专业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {Int} id:专业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: 专业ID
     *      department_id:院系ID
     *      majorNumber:专业代码
     *      majorName:专业名称
     *      needYear:修业年限
     *      majorTypeNumber:专业类型代码
     *      majorTypeName:专业类型名称
     *      major_top_number:专业大类代码
     *      major_top_type:专业大类名称
     *      batch:批次
     *      science_class:招生科类
     *      subject:选择科目
     *      professional_certificate:专业证书
     *      academic_degree:专业授予学位
     *      fiveYearsOfGraduation:毕业5年月薪
     *      training_course:修学课程
     *      intro:简介
     * }
     * ]
     * }
     *
     */
    public function getCollegeMajorInfo()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeMajorInfo';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /index/collegeAdmin/getAioScience 省份获取学科类型列表
     * @apiVersion 1.0.0
     * @apiName getAioScience
     * @apiGroup College
     * @apiDescription 省份获取学科类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {Int} province_id:省份ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: 学科类型ID
     *      name:学科类型名称
     * }
     * ]
     * }
     *
     */
    public function getAioScience()
    {
       $province_id = input('param.province_id', '', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/collegeAdmin/getAioScience';
        $param['province_id'] = $province_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getYear 院校年份
     * @apiVersion 1.0.0
     * @apiName getYear
     * @apiGroup College
     * @apiDescription 院校年份
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id:大学ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      value: 年份id
     *      name:年份名称
     * }
     * ]
     * }
     *
     */
    public function getYear()
    {
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getYear';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/getCollegeScoreInfo 录取分数专业分数信息(aio)
     * @apiVersion 1.0.0
     * @apiName getCollegeScoreInfo
     * @apiGroup College
     * @apiDescription 录取分数专业分数信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int}    college_id 院校ID.
     * @apiParam {Int}    province_id 省份ID.
     * @apiParam {Int}    year 年份.
     * @apiParam {Int}    science 文理科.
     * @apiParam {String} title 专业代码或专业名称 (可选)
     * @apiParam {String} major_type_number 专业类型代码. (可选)
     * @apiParam {Int}    page 当前页数(可选)
     * @apiParam {Int}    pagesize 当前条数. (可选)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
         * @apiSuccessExample  {json} Success-Response:
     * {
     * code: "1",
     * msg: "操作成功",
     * data: { [
     * {
     *    id: 录取分数ID,
     *    majorTypeNumber:专业类型代码
     *    majorTypeNumber:专业类型名称
     *    majorName: 专业代码,
     *    majorNumber: 专业名称,
     *    batch: 批次,
     *    numberOfAdmissions: 实际人数,
     *    science: 招生类别,
     *    max: 最高分,
     *    min: 最低分,
     *    avg: 平均分,
     * }
     * ]
     * }
     * }
     */

    public function getCollegeScoreInfo()
    {   
        $college_id = input('param.college_id', '', 'int');
        $enrollmentYear = input('param.year', '', 'int');
        $science = input('param.science', '', 'int');
        $province_id = input('param.province_id', '');
        $title = input('param.title', '', 'htmlspecialchars');
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeScoreInfo';
        $param['college_id'] = $college_id;
        $param['year'] = $enrollmentYear;
        $param['science'] = $science;
        $param['title'] = $title;
        $param['province_id'] = $province_id;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /Api/aio.AioCollege/occupationTypeList 获取职业类型列表
     * @apiVersion 1.0.0
     * @apiName occupationTypeList
     * @apiGroup College
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
     *   pic_url:图片地址
     *   industry_name:行业名称
     * }
     * ]
     * }
     */
    public function occupationTypeList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page= input('param.page', '1', 'intval');

        $occupation_type_name = input('param.occupationTypeName', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/occupationTypeList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['occupationTypeName'] = $occupation_type_name;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);

    }



    /**
     * @api {post} /api/aio.AioCollege/occupationList 获取职业列表
     * @apiVersion 1.0.0
     * @apiName occupationList
     * @apiGroup Occupation
     * @apiDescription 获取职业类型列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
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
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/occupationList';
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/occupationInfo 查看职业信息
     * @apiVersion 1.0.0
     * @apiName occupationInfo
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
    public function occupationInfo()
    {
        $occupation_id = input('param.occupation_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/editOccupation';
        $param['occupation_id'] = $occupation_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $v_param['key_id'] = 'occupation_id';
        $v_param['table'] = 'OccupationInfo';
        $v_url =  $college_api.'/index/CollegeAdmin/addBrowView';
        $v_param['id'] = $occupation_id;
        curl_api($v_url, $v_param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/educationTypeListV41 专科本科类型
     * @apiVersion 1.0.0
     * @apiName educationTypeListV41
     * @apiGroup Major
     * @apiDescription 专业大类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     *   {
     *   code: "1",
     *   msg: "操作成功",
     *   data: [
     *   {
     *       type_number: "B",
     *       type_name: "本科"
     *   },
     *   {
     *       type_number: "C",
     *       type_name: "专科"
     *   }
     *   ]
     *   }
     */
    public function educationTypeListV41()
    {
        $education_type = input('param.educationType',  'B', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/educationTypeListV41';
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorEducationListV41 专业大类信息列表
     * @apiVersion 1.0.0
     * @apiName majorEducationListV41
     * @apiGroup Major
     * @apiDescription 专业大类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} educationType 专科本科类型（B 本科  C专科）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
          * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 专业大类代码,
     *   majorTypeName: "专业大类名称",
     * }
     * ]
     * }
     */
    public function majorEducationListV41()
    {
        $education_type = input('param.educationType',  'B', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorEducationListV41';
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorTypeEducationListV41 专业门类信息列表
     * @apiVersion 1.0.0
     * @apiName majorTypeEducationListV41
     * @apiGroup Major
     * @apiDescription 专业门类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorTypeName 专业大类代码.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
          * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 专业门类代码,
     *   majorTypeName: "专业门类名称",
     * }
     * ]
     * }
     */

    public function majorTypeEducationListV41()
    {
        $major_type_number = input('param.majorTypeNumber',  '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorTypeEducationListV41';
        $param['majorTypeNumber'] = $major_type_number;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorAllListv41 专业详情列表
     * @apiVersion 1.0.0
     * @apiName majorAllListv41
     * @apiGroup Major
     * @apiDescription 专业详情列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} title 专业名称或者专业代码（可选）.
     * @apiParam {String} majorTopNumber 专业大类代码.
     * @apiParam {String} majorTypeNumber 专业门类代码 （可选）.
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
     *   type_id: ID
     *   type_Number:专业类型代码
     *   type_name:专业类型名称
     *   majorTypeNumber: 专业大类代码,
     *   majorTypeName: "专业大类名称",
     *   major_name: 专业代码,
     *   major_number: "专业名称",
     * }
     * ]
     * }
     */
    public function majorAllListv41()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $title = input('param.title', '', 'htmlspecialchars');
        $education_type = input('param.educationType', '', 'htmlspecialchars');
        $major_top_number = input('param.majorTopNumber', '', 'htmlspecialchars');
        $major_type_number = input('param.majorTypeNumber', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorAllListv41';

        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['title'] = $title;
        $param['majorTopNumber'] = $major_top_number;
        $param['majorTypeNumber'] = $major_type_number;
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        $v_param['key_id'] = 'type_id';
        $v_param['table'] = 'MajorInfo';
        $v_url =  $college_api.'/index/CollegeAdmin/addBrowView';
        foreach ($data['data'] as $key => $value) {
            $v_param['id'] = $value['type_id'];
            curl_api($v_url, $v_param, 'post');
        }
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/educationTypeList 专科本科类型
     * @apiVersion 1.0.0
     * @apiName educationTypeList
     * @apiGroup Major
     * @apiDescription 专业大类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     *   {
     *   code: "1",
     *   msg: "操作成功",
     *   data: [
     *   {
     *       type_number: "B",
     *       type_name: "本科"
     *   },
     *   {
     *       type_number: "C",
     *       type_name: "专科"
     *   }
     *   ]
     *   }
     */
    public function educationTypeList()
    {
        $education_type = input('param.educationType',  'B', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/educationTypeList';
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorEducationList 专业大类信息列表
     * @apiVersion 1.0.0
     * @apiName majorEducationList
     * @apiGroup Major
     * @apiDescription 专业大类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} educationType 专科本科类型（B 本科  C专科）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 专业大类代码,
     *   majorTypeName: "专业大类名称",
     * }
     * ]
     * }
     */
    public function majorEducationList()
    {
        $education_type = input('param.educationType',  'B', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorEducationList';
        $param['educationType'] = $education_type;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorTypeEducationList 专业门类信息列表
     * @apiVersion 1.0.0
     * @apiName majorTypeEducationList
     * @apiGroup Major
     * @apiDescription 专业门类信息列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} majorTypeName 专业大类代码.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 专业门类代码,
     *   majorTypeName: "专业门类名称",
     * }
     * ]
     * }
     */

    public function majorTypeEducationList()
    {
        $major_type_number = input('param.majorTypeNumber',  '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorTypeEducationList';
        $param['majorTypeNumber'] = $major_type_number;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorAllList 专业详情列表
     * @apiVersion 1.0.0
     * @apiName majorAllList
     * @apiGroup Major
     * @apiDescription 专业详情列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} title 专业名称或者专业代码（可选）.
     * @apiParam {String} majorTopNumber 专业大类代码.
     * @apiParam {String} majorTypeNumber 专业门类代码 （可选）.
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
     *   type_id: ID
     *   type_Number:专业类型代码
     *   type_name:专业类型名称
     *   majorTypeNumber: 专业大类代码,
     *   majorTypeName: "专业大类名称",
     *   major_name: 专业代码,
     *   major_number: "专业名称",
     * }
     * ]
     * }
     */
    public function majorAllList()
    {
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');
        $title = input('param.title', '', 'htmlspecialchars');
        $major_top_number = input('param.majorTopNumber', '', 'htmlspecialchars');
        $major_type_number = input('param.majorTypeNumber', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorAllList';

        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['title'] = $title;
        $param['majorTopNumber'] = $major_top_number;
        $param['majorTypeNumber'] = $major_type_number;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        $v_param['key_id'] = 'type_id';
        $v_param['table'] = 'MajorInfo';
        $v_url =  $college_api.'/index/CollegeAdmin/addBrowView';
        foreach ($data['data'] as $key => $value) {
            $v_param['id'] = $value['type_id'];
            curl_api($v_url, $v_param, 'post');
        }
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/majorInfo 专业详情（专业简介 和 专业视频）
     * @apiVersion 1.0.0
     * @apiName majorInfo
     * @apiGroup Major
     * @apiDescription 专业详情（专业简介 和 专业视频）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} type_id 专业类型ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
          * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   type_id: ID
     *   majorNumber: 专业代码
     *   majorName:专业名称
     *   grantDegree: 授予学位,
     *   needYear: 修学年限,
     *   professionalIntroduction: 专业简介,
     *   pic_url: "图片地址",
     *   video_url: 视频地址,
     *   type_name: "所属学科",
     *   top_name: 所属大类,
     * }
     * ]
     * }
     */
    public function majorInfo()
    {
        $type_id = input('param.type_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorInfo';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/majorCollegeList 专业详情（开设院校）
     * @apiVersion 1.0.0
     * @apiName majorCollegeList
     * @apiGroup Major
     * @apiDescription 专业详情（开设院校）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} type_id 专业类型ID
     * @apiParam {Int} pagesize 分页数量
     * @apiParam {Int} page 当前页
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   college_id:  大学ID
     *   college_name: 大学名称
     *   college_code:   大学代码
     * }
     * ]
     * }
     */
    public function majorCollegeList()
    {
        $type_id = input('param.type_id', '', 'intval');
        $page = input('param.page', '1', 'intval');
        $pagesize = input('param.pagesize', '100', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorCollegeList';
        $param['type_id'] = $type_id;
        $param['pagesize'] = $pagesize;
        $param['page'] = $page;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        $res_data = $data['data']['list'];
        $data['data'] = $res_data;
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/majorOccupationList 专业详情（相关职业）
     * @apiVersion 1.0.0
     * @apiName majorOccupationList
     * @apiGroup Major
     * @apiDescription 专业详情（相关职业）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} type_id 专业类型ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   occupation_id:  职业ID
     *   occupation_name: 职业名称
     * }
     * ]
     * }
     */
    public function majorOccupationList()
    {
        $type_id = input('param.type_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/majorOccupationList';
        $param['type_id'] = $type_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/occupationMajorList 职业详情 (关联专业)
     * @apiVersion 1.0.0
     * @apiName occupationMajorList
     * @apiGroup Major
     * @apiDescription 专业详情（相关职业）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} occupation_id 职业ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   type_id: ID
     *   majorNumber: 专业代码
     *   majorName:专业名称
     * }
     * ]
     * }
     */
    public function occupationMajorList()
    {
        $occupation_id = input('param.occupation_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/occupationMajorList';
        $param['occupation_id'] = $occupation_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /Api/aio.AioCollege/getVideoTypeList 大学专业职业视频列表
     * @apiVersion              1.0.0
     * @apiName                 getVideoTypeList
     * @apiGROUP                APi
     * @apiDescription          大学专业职业视频列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          type   视频类型（1大学 2专业 3 职业）
     * @apiParam {Int}          id    对应的大学专业职业 ID
     * @apiParam {Int}          page  页数
     * @apiParam {int}          pagesize 分页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: ,
     *      title: 名称,
     *      content: "如果是play_type是1的话，就是admin_upload的id，不是就是网页或者视频源地址",
     *      play_type: 视频源来源地址,1:本地上传,2:iframe网页来源,3:外链视频源地址,
     *      cover: 封面，列表页图片url地址
     * }
     * ]
     * }
     *
     */
    public function getVideoTypeList()
    {
        $video = new video();
        $type = input('param.type', '1', 'intval');
        $term_type = input('param.term_type', '1', 'intval');
        $id = input('param.id', '1', 'int');
        $c_id = input('param.college_id', '', 'int');
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '8', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $s_where['term_type'] = $term_type;
        $s_where['c_id'] = $c_id;
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $video->getVideoCollegeList($type, $id, $limit, 'id desc',$s_where);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post}  /api/aio.AioCollege/hotCollege 热门院校列表(aio)
     * @apiVersion 1.0.0
     * @apiName hotCollege
     * @apiGroup College
     * @apiDescription 热门院校列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  college_id:大学ID.
     *  title: 大学名称.
     *  thumb: 校徽图片
     * }
     * ]
     * }
     */
    public function hotCollege()
    {
        $pagesize = input('param.pagesize', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/collegeAdmin/hotCollege';
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post}  /api/aio.AioCollege/beautCollege 最美院校列表(aio)
     * @apiVersion 1.0.0
     * @apiName beautCollege
     * @apiGroup College
     * @apiDescription 最美院校列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  college_id:大学ID.
     *  title: 大学名称.
     *  thumb: 校徽图片
     * }
     * ]
     * }
     */
    public function beautCollege()
    {
        $pagesize = input('param.pagesize', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/collegeAdmin/beautCollege';
        $param['pagesize'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /api/aio.AioCollege/hotMajor  热门专业
     * @apiVersion 1.0.0
     * @apiName hotMajor
     * @apiGroup Major
     * @apiDescription 热门专业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} pagesize 分页数.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   major_id: 专业ID
     *   majorName 专业名称
     *   type_id: 专业类型ID
     *   pic_url: 图片地址
     * }
     * ]
     * }
     */
    public function hotMajor()
    {
        $pagesize = input('param.pagesize', '9', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Major/hotMajor';
        $param['num'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /api/aio.AioCollege/hotOccupation 热门职业列表
     * @apiVersion 1.0.0
     * @apiName hotOccupation
     * @apiGroup Occupation
     * @apiDescription 热门职业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} pagesize 分页数
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
     *      pic_url: 图片地址
     * }
     * ]
     * }
     */
    public function hotOccupation()
    {
        $pagesize = input('param.pagesize', '9', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Occupation/hotOccupation';
        $param['num'] = $pagesize;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

}

