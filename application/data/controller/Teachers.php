<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Teachers extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }
    
     /**
     * @api {post} /data/Teachers/teachersList 师资数据列表(后台)
     * @apiVersion 1.0.0
     * @apiName teachersList
     * @apiGroup Teachers
     * @apiDescription 师资数据列表(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} num  页数
     * @apiParam {int} college_id 院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     * @apiParam {String} id:师资id
     * @apiParam {String} name:老师姓名
     * @apiParam {Int} sex:性别(1男 2女)
     * @apiParam {String} img:图片路径
     * @apiParam {String} position:职位
     * @apiParam {String} degree:学位
     * @apiParam {int} major_id:授课专业ID
     * @apiParam {String} brief_introduction:人物简介
     * @apiParam {String} achievement:主要成就
     * }
     * ]
     * }
     *
     */
    public function teachersList()
    {
        $param['college_id'] = input('param.college_id', '', 'intval');
        $param['pagesize'] = input('param.pagesize', '', 'intval');
        $param['page'] = input('param.page', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Teachers/teachersList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Teachers/addTeachers 添加师资数据（后台）
     * @apiVersion 1.0.0
     * @apiName addRedisTeachers
     * @apiGroup Teachers
     * @apiDescription 添加师资数据（后台）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name:老师姓名
     * @apiParam {Int} sex:性别(1男 2女)
     * @apiParam {String} img:图片路径
     * @apiParam {Int} college_id  院校ID
     * @apiParam {String} position:职位
     * @apiParam {String} degree:学位
     * @apiParam {int} major_id:授课专业ID
     * @apiParam {String} brief_introduction:人物简介
     * @apiParam {String} achievement:主要成就
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息..
     */
    public function addTeachers()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Teachers/addTeachers';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/Teachers/editTeachers 师资编辑信息(后台)
     * @apiVersion 1.0.0
     * @apiName editTeachers
     * @apiGroup Teachers
     * @apiDescription 师资编辑信息(后台)
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
     *       name:老师姓名
     *       sex:性别(1男 2女)
     *       img:图片路径
     *       college_id  院校ID
     *       position:职位
     *       degree:学位
     *       major_id:授课专业ID
     *       brief_introduction:人物简介
     *       achievement:主要成就
     * }
     * ]
     * }
     *
     */
    public function editTeachers()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Teachers/editTeachers';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Teachers/saveTeachers 修改提交师资信息(后台)
     * @apiVersion 1.0.0
     * @apiName saveTeachers
     * @apiGroup Teachers
     * @apiDescription 修改提交师资信息(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id:师资id
     * @apiParam {String} name:老师姓名
     * @apiParam {Int} sex:性别(1男 2女)
     * @apiParam {String} img:图片路径
     * @apiParam {Int} college_id  院校ID
     * @apiParam {String} position:职位
     * @apiParam {String} degree:学位
     * @apiParam {int} major_id:授课专业ID
     * @apiParam {String} brief_introduction:人物简介
     * @apiParam {String} achievement:主要成就
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveTeachers()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Teachers/saveTeachers';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get} /data/Teachers/deleteTeachers 师资信息删除操作
     * @apiVersion 1.0.0
     * @apiName deleteTeachers
     * @apiGroup Teachers
     * @apiDescription 师资信息删除操作
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id   师资id (多个用‘,’分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteTeachers()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Teachers/deleteRedisTeachers';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

}
