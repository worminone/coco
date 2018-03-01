<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Alumnus extends Admin
{
    
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/Alumnus/alumnusList 校友数据列表(后台)
     * @apiVersion 1.0.0
     * @apiName alumnusList
     * @apiGroup Alumnus
     * @apiDescription 校友数据列表(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} pagesize  页数
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
     *    id: 校友ID,
     *    name: 校友名字,
     *    img: 头像地址,
     *    occupation_name: 职业名称,
     *    college_id: 院校ID,
     *    graduation_college: 毕业院校,
     *    major_name: 专业名称,
     *    update_time: 新增时间,
     * }
     * ]
     * }
     *
     */
    public function alumnusList()
    {
        $param['college_id'] = input('param.college_id', '', 'intval');
        $param['pagesize'] = input('param.pagesize', '10', 'intval');
        $param['page'] = input('param.page', '1', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Alumnus/alumnusList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Alumnus/addAlumnus 添加校友数据（后台）
     * @apiVersion 1.0.0
     * @apiName addRedisAlumnus
     * @apiGroup Alumnus
     * @apiDescription 添加校友数据（后台）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name:校友姓名
     * @apiParam {String} occupation_name:职业
     * @apiParam {int} college_id:当前大学ID
     * @apiParam {String} graduation_college:毕业院校
     * @apiParam {String} major_name:专业名称
     * @apiParam {int} sex:性别（1男2女）
     * @apiParam {String} nationality:籍贯
     * @apiParam {String} birthday:生日
     * @apiParam {String} synopsis:人物简介
     * @apiParam {String} experience:人物经历
     * @apiParam {String} achievement:主要成就
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息..
     */
    public function addAlumnus()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Alumnus/addAlumnus';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/Alumnus/editAlumnus 校友编辑信息(后台)
     * @apiVersion 1.0.0
     * @apiName editAlumnus
     * @apiGroup Alumnus
     * @apiDescription 校友编辑信息(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} id:校友id
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
     *    name:校友姓名
     *    occupation_name:职业
     *    college_id:当前大学ID
     *    graduation_college:毕业院校
     *    major_name:专业名称
     *    sex:性别（1男2女）
     *    nationality:籍贯
     *    birthday:生日
     *    synopsis:人物简介
     *    experience:人物经历
     *    achievement:主要成就
     * }
     * ]
     * }
     *
     */
    public function editAlumnus()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Alumnus/editAlumnus';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Alumnus/saveAlumnus 修改提交校友信息(后台)
     * @apiVersion 1.0.0
     * @apiName saveAlumnus
     * @apiGroup Alumnus
     * @apiDescription 修改提交校友信息(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id:校友id
     * @apiParam {String} name:校友姓名
     * @apiParam {int} occupation_id:
     * @apiParam {String} occupation_name:职业
     * @apiParam {int} college_id:当前大学ID
     * @apiParam {String} graduation_college:毕业院校
     * @apiParam {int} major_name:专业名字
     * @apiParam {String} sex:性别（1男2女）
     * @apiParam {String} nationality:籍贯
     * @apiParam {String} birthday:生日
     * @apiParam {String} synopsis:人物简介
     * @apiParam {String} experience:人物经历
     * @apiParam {String} achievement:主要成就
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveAlumnus()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Alumnus/saveAlumnus';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /index/Alumnus/deleteAlumnus 校友删除操作
     * @apiVersion 1.0.0
     * @apiName deleteAlumnus
     * @apiGroup Alumnus
     * @apiDescription 校友删除操作
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id:校友id (多个用‘,’分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteAlumnus()
    {
        $id = input('param.id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/Alumnus/deleteRedisAlumnus';
        $param['id'] = $id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
