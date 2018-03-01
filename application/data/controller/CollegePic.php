<?php
namespace app\data\controller;

use think\Request;
use app\common\controller\Admin;

class CollegePic extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/CollegePic/collegePicList 院校风采数据列表(后台)
     * @apiVersion 1.0.0
     * @apiName collegePicList
     * @apiGroup CollegePic
     * @apiDescription 院校风采数据列表(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} user_id 用户ID.
     * @apiParam {int} page 当前页数.
     * @apiParam {int} type 类型.
     * @apiParam {int} title 标题.
     * @apiParam {int} college_id 院校ID.
     * @apiParam {int} pagesize 分页数.
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
     * }
     * ]
     * }
     *
     */
    public function collegePicList()
    {
        $param['type']  = input('param.type', '1', 'intval');
        $param['pagesize']   = input('param.pagesize', '10', 'intval');
        $param['college_id']   = input('param.college_id', '', 'intval');
        $param['title'] = input('param.name', '', 'htmlspecialchars');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePic/collegePicList';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePic/addCollegePic 添加院校风采数据(后台)
     * @apiVersion 1.0.0
     * @apiName addCollegePic
     * @apiGroup CollegePic
     * @apiDescription 添加院校风采数据(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} title:标题
     * @apiParam {int} pic_url: 图片路径
     * @apiParam {int} college_id:当前大学ID
     * @apiParam {int} type:类型
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addCollegePic()
    {
        $param = input('param.');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePic/addCollegePic';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegePic/saveCollegePic 提交院校风采信息(后台)
     * @apiVersion 1.0.0
     * @apiName saveCollegePic
     * @apiGroup CollegePic
     * @apiDescription 提交院校风采信息(后台)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} pic_id:图片id
     * @apiParam {String} title:标题
     * @apiParam {int} pic_url: 图片路径
     * @apiParam {int} college_id:当前大学ID
     * @apiParam {int} type:类型
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveCollegePic()
    {
        $param = input('param.');
        //模拟参数
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePic/saveCollegePic';
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get} /data/CollegePic/deleteCollegePic 校园风采删除操作
     * @apiVersion 1.0.0
     * @apiName deleteCollegePic
     * @apiGroup CollegePic
     * @apiDescription 校园风采删除操作
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} pic_id:图片id (多个用‘,’分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteCollegePic()
    {
        $pic_id = input('param.pic_id');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePic/deleteRedisCollegePic';
        $param['pic_id'] = $pic_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    //设置封面页面
    /**
     * @api {get} /data/CollegePic/setCover 校园风采设置封面页面
     * @apiVersion 1.0.0
     * @apiName setCover
     * @apiGroup CollegePic
     * @apiDescription 校园风采设置封面页面-10
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} pic_id 图片ID.
     * @apiParam {int} college_id 院校ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function setCover()
    {
        $pic_id = input('param.pic_id', '', 'intval');
        $college_id = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegePic/setCover';
        $param['pic_id'] = $pic_id;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
