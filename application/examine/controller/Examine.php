<?php
namespace app\examine\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Examine extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /examine/Examine/redisCollegeList 院校后台审核列表
     * @apiVersion 1.0.0
     * @apiName redisCollegeList
     * @apiGroup Examine
     * @apiDescription 院校后台审核列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  user_id 用户ID.
     *  college_id:大学ID.
     *  title:大学名称.
     *  region_id:市区ID.
     *  schools_type:院校类别(综合类、理工类、师范类、农林类、政法类、医药类、
     *   财经类、民族类、语言类、艺术类、体育类、军事类、游类).
     *  collegeCode:院校代码.
     *  collegeNature:办学性质（公立 ,私立）.
     *  collegesAndUniversities:院校隶属.
     *  principal：校长
     *  teacher_num:教工人数.
     *  master_num:硕士人数.
     *  doctor_num:博士人数.
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
     *  operation: "DELETE",
     *  后台操作状态（UPDATE 修改）
     *  redis_time: 提交后台时间
     * }
     * ]
     * }
     *
     */
    public function redisCollegeList()
    {
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/College/redisCollegeList';
        $param = input('param.');
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /examine/Examine/redisCollegeInfo 院校后台审核详情
     * @apiVersion 1.0.0
     * @apiName redisCollegeList
     * @apiGroup Examine
     * @apiDescription 院校后台审核列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} admin_code_id 审核ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  user_id 用户ID.
     *  college_id:大学ID.
     *  title:大学名称.
     *  region_id:市区ID.
     *  schools_type:院校类别(综合类、理工类、师范类、农林类、政法类、医药类、
     *   财经类、民族类、语言类、艺术类、体育类、军事类、游类).
     *  collegeCode:院校代码.
     *  collegeNature:办学性质（公立 ,私立）.
     *  collegesAndUniversities:院校隶属.
     *  principal：校长
     *  teacher_num:教工人数.
     *  master_num:硕士人数.
     *  doctor_num:博士人数.
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
     *  operation: "DELETE",
     *  后台操作状态（UPDATE 修改）
     *  redis_time: 提交后台时间
     * }
     * ]
     * }
     *
     */
    public function redisCollegeInfo()
    {
        $admin_code_id = input('param.admin_code_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/College/redisCollegeInfo';
        $param['admin_code_id'] = $admin_code_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /examine/Examine/saveRedisCollegeSubmit 后台审核提交院校信息
     * @apiVersion 1.0.0
     * @apiName saveRedisCollegeSubmit
     * @apiGroup Examine
     * @apiDescription 后台审核提交院校信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} admin_code_id 审核ID.
     * @apiParam {int} is_approval 审核(1审核通过，2审核未通过).
     * @apiParam {int} user_name 审核人.
     * @apiParam {int} reason 审核原因.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveRedisCollegeSubmit()
    {
        $admin_code_id = input('param.admin_code_id', '', 'intval');
        $is_approval = input('param.is_approval', '1', 'intval');
        $user_name = input('param.user_name');
        $reason = input('param.reason');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/College/redisCollegeList';
        $param['admin_code_id'] = $admin_code_id;
        $param['is_approval'] = $is_approval;
        $param['user_name'] = $user_name;
        $param['reason'] = $reason;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get} /examine/Examine/redisCollegeVideoList 后台获取视频列表
     * @apiVersion 1.0.0
     * @apiName redisCollegeVideoList
     * @apiGroup Examine
     * @apiDescription 后台获取视频列表-4
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   class_id: 类型ID(1 院校视频 专业视频).
     *   college_id:大学ID
     *   major_id:大学专业ID
     *   vu:视频标识码
     *   intro:简介
     *   title:ceshi
     *   ls_video_id:43888743
     *   initial_size:70.53 MB
     *   video_duration:19分32秒
     *   video_img: 图片地址
     *   video_status:播放状态(正常播放|10,转码失败|20,审核失败|21,片源错误|22,片源错误|23,上传失败|24,处理中|30,审核中|31,无视频源|32,上传初始化|33,视频上传中|34,停用|40,)
     *   video_id:视频ID
     *   is_approval 审核状态（1.审核通过 2审核未通过）
     *   update_time 上传时间
     * }
     * ]
     * }
     *
     */
    public function redisCollegeVideoList()
    {
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegeVideo/redisCollegeVideoList';
        $param = input('param.');
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get} /examine/Examine/redisCollegeVideoInfo 后台获取视频详情
     * @apiVersion 1.0.0
     * @apiName redisCollegeVideoInfo
     * @apiGroup Examine
     * @apiDescription 后台获取视频详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} admin_code_id 列表ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   class_id: 类型ID(1 院校视频 专业视频).
     *   college_id:大学ID
     *   major_id:大学专业ID
     *   vu:视频标识码
     *   intro:简介
     *   title:ceshi
     *   ls_video_id:43888743
     *   initial_size:70.53 MB
     *   video_duration:19分32秒
     *   video_img: 图片地址
     *   video_status:播放状态(正常播放|10,转码失败|20,审核失败|21,片源错误|22,片源错误|23,上传失败|24,处理中|30,审核中|31,无视频源|32,上传初始化|33,视频上传中|34,停用|40,)
     *   video_id:视频ID
     *   is_approval 审核状态（1.审核通过 2审核未通过）
     *   update_time 上传时间
     * }
     * ]
     * }
     *
     */
    public function redisCollegeVideoInfo()
    {
        $admin_code_id = input('param.admin_code_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/College/redisCollegeVideoInfo';
        $param['admin_code_id'] = $admin_code_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    
    /**
     * @api {get} /examine/Examine/addRedisCollegeVideoSubmit 视频后台提交审核
     * @apiVersion 1.0.0
     * @apiName addRedisCollegeVideoSubmit
     * @apiGroup Examine
     * @apiDescription 视频后台提交审核
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} admin_code_id 列表ID.
     * @apiParam {int} is_approval 审核(1审核通过，2审核未通过).
     * @apiParam {String} reason 审核失败原因.
     * @apiParam {String} user_name 审核人.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addRedisCollegeVideoSubmit()
    {
        $admin_code_id = input('param.admin_code_id', '', 'intval');
        $is_approval = input('param.is_approval', '1', 'intval');
        $user_name = input('param.user_name');
        $reason = input('param.reason');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegeVideo/addRedisCollegeVideoSubmit';
        $param['admin_code_id'] = $admin_code_id;
        $param['is_approval'] = $is_approval;
        $param['user_name'] = $user_name;
        $param['reason'] = $reason;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get} /examine/Examine/redisCollegePicList 后台获取校园风采列表
     * @apiVersion 1.0.0
     * @apiName redisCollegePicList
     * @apiGroup Examine
     * @apiDescription 后台获取校园风采列表-3
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} college_name 大学名称.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} pagesize 每页条数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   pic_id: id,
     *   title: 图片名称,
     *   pic_url: 图片地址,
     *   type: 1, 类型(1 教学环境, 2 住宿环境, 3 生活环境, 4 食堂环境, 5 社团环境）,
     *   front_flag: 推荐(0:否 1：是）
     *   type_name: "教学环境"
     *   is_approval 审核成功（1 成功 2 失败） == 此字段存在审核中
     * }
     * ]
     * }
     *
     */

    public function redisCollegePicList()
    {
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegePic/redisCollegePicList';
        $page = input('param.page', '1', 'intval');
        $pagesize = input('param.pagesize', '10', 'intval');
        $college_name = input('param.college_name', '', 'htmlspecialchars');
        $param['admin_key'] = $admin_key;
        $param['num'] = $pagesize;
        $param['page'] = $page;
        $param['college_name'] = $college_name;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /examine/Examine/addRedisCollegePicSubmit 院校风采后台提交审核
     * @apiVersion 1.0.0
     * @apiName addRedisCollegePicSubmit
     * @apiGroup Examine
     * @apiDescription 院校风采后台提交审核
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} admin_code_id 列表ID.
     * @apiParam {int} is_approval 审核(1审核通过，2审核未通过).
     * @apiParam {String} reason 审核失败原因.
     * @apiParam {String} user_name 审核人.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addRedisCollegePicSubmit()
    {
        $admin_code_id = input('param.admin_code_ids');
        $is_approval = input('param.is_approval', '1', 'intval');
        $user_name = input('param.user_name', '', 'htmlspecialchars');
        $reason = input('param.reason', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegePic/addRedisCollegePicSubmit';
        $param['admin_code_id'] = $admin_code_id;
        $param['is_approval'] = $is_approval;
        $param['user_name'] = $user_name;
        $param['reason'] = $reason;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


     /**
     * @api {get} /examine/Examine/redisCollegePicInfo 后台获取校园风采信息
     * @apiVersion 1.0.0
     * @apiName redisCollegePicInfo
     * @apiGroup Examine
     * @apiDescription 后台获取校园风采信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} admin_code_id 列表ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   pic_id: id,
     *   title: 图片名称,
     *   pic_url: 图片地址,
     *   type: 1, 类型(1 教学环境, 2 住宿环境, 3 生活环境, 4 食堂环境, 5 社团环境）
     *   front_flag: 推荐(0:否 1：是）
     *   type_name: "教学环境"
     *   operation: "DELETE",
     *   后台操作状态（ADD 新增, UPDATE 修改, DELETE 删除）
     *   redis_time: 提交后台时间
     * }
     * ]
     * }
     *
     */
    public function redisCollegePicInfo()
    {
        $admin_code_id = input('param.admin_code_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/CollegePic/redisCollegePicInfo';
        $param['admin_code_id'] = $admin_code_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /examine/Examine/getApprovalList 图片审核后的列表记录
     * @apiVersion 1.0.0
     * @apiName getApprovalList
     * @apiGroup Examine
     * @apiDescription 图片审核后的列表记录
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} college_name 大学名称.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} page_size 当前条数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     * id: 21,  ID
     * college_id: 院校ID
     * college_name: 院校名称
     * title: 标题
     * reason: 原因
     * user_id: 修改用户
     * pic_id, 图片ID
     * is_approval:审核 （1通过 2不通过）
     * type_name: 图片类型,
     * }
     * ]
     * }
     *
     */
    public function getApprovalList()
    {
        $update_type = input('param.update_type', '4', 'intval');
        $college_name = input('param.college_name', '', 'htmlspecialchars');
        $is_approval = input('param.is_approval', '', 'intval');
        $pagesize = input('param.pagesize', '10', 'intval');
        $page = input('param.page', '1', 'intval');

        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url = $college_api.'/index/OperationLog/getApprovalList';
        $param['update_type'] = $update_type;
        $param['num'] = $pagesize;
        $param['is_approval'] = $is_approval;
        $param['page'] = $page;
        $param['title'] = $college_name;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }
}
