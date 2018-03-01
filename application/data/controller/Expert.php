<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Expert extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/Expert/getUserList 专家数据库列表
     * @apiVersion 1.0.0
     * @apiName getUserList
     * @apiGroup User
     * @apiDescription 专家数据库列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} experts_name 姓名.
     * @apiParam {String} expert_tag   资质.
     * @apiParam {Int}    pagesize     当页条数.
     * @apiParam {Int}    page         当前页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: [
     *  {
     *      id: ID,
     *      experts_name:       用户名,
     *      pic_url:            头像地址,
     *      help_people:        帮助人数,
     *      authentication:     资质认证,
     *      answered:           回应时长,
     *      counseling:         咨询时间段,
     *      auth:               资质（文本）,
     *      profiles:           砖家简介,
     *      personal_src:       形象照地址,
     *      sex:                性别,
     *  }
     *  ]
     *  }
     */
    public function getUserList(){

        $param['experts_name'] = input('param.experts_name', '','htmlspecialchars');
        $param['expert_tag'] = input('param.expert_tag', '','intval');
        $param['pagesize'] = input('pagesize', '10', 'intval');
        $param['page'] = input('page', '1', 'intval');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/getUserList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Expert/addUserInfo 新增专家数据库
     * @apiVersion 1.0.0
     * @apiName addUserInfo
     * @apiGroup User
     * @apiDescription 新增专家数据库
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} experts_name:       用户名,
     * @apiParam {String} pic_url:            头像地址,
     * @apiParam {Int}    help_people:        帮助人数,
     * @apiParam {String} authentication:     资质认证,
     * @apiParam {String} answered:           回应时长,
     * @apiParam {String} counseling:         咨询时间段,
     * @apiParam {String} auth:               资质（文本）,
     * @apiParam {String} profiles:           砖家简介,
     * @apiParam {String} personal_src:       形象照地址,
     * @apiParam {String} expert_tag:         擅长领域 (多个‘,’分割《文字》),
     * @apiParam {Int} sort                   推荐排序
     * @apiParam {Int} is_top                 是否推荐
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addUserInfo()
    {
        $param = input('param.');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/addUserInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
    /**
     * @api {post} /data/Expert/editUserInfo 专家数据库信息
     * @apiVersion 1.0.0
     * @apiName editUserInfo
     * @apiGroup User
     * @apiDescription 专家数据库信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id   用户ID.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: [
     *  {
     *      id:                 ID,
     *      experts_name:       用户名,
     *      pic_url:            头像地址,
     *      help_people:        帮助人数,
     *      authentication:     资质认证,
     *      answered:           回应时长,
     *      counseling:         咨询时间段,
     *      auth:               资质（文本）,
     *      profiles:           砖家简介,
     *      personal_src:       形象照地址,
     *      expert_tag          擅长领域
     *  }
     *  ]
     *  }
     */
    public function editUserInfo()
    {
        $param['id'] = input('param.id', '', 'int');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/editUserInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Expert/saveUserInfo 修改专家数据库
     * @apiVersion 1.0.0
     * @apiName saveUserInfo
     * @apiGroup User
     * @apiDescription 修改专家数据库
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id                  用户ID.
     * @apiParam {String} experts_name:       用户名,
     * @apiParam {String} pic_url:            头像地址,
     * @apiParam {Int}    help_people:        帮助人数,
     * @apiParam {String} authentication:     资质认证,
     * @apiParam {String} answered:           回应时长,
     * @apiParam {String} counseling:         咨询时间段,
     * @apiParam {String} auth:               资质（文本）,
     * @apiParam {String} profiles:           砖家简介,
     * @apiParam {String} personal_src:       形象照地址,
     * @apiParam {String} expert_tag:         擅长领域 (多个‘,’分割《文字》),
     * @apiParam {Int} sort                   推荐排序
     * @apiParam {Int} is_top                 是否推荐
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveUserInfo()
    {
        $param = input('param.');
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/saveUserInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Expert/typeList 获取擅长领域列表
     * @apiVersion 1.0.0
     * @apiName typeList
     * @apiGroup User
     * @apiDescription 获取擅长领域列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: [
     *{
     *         id: "1",
     *         name: "选课问题"
     *         },
     *         {
     *         id: "2",
     *         name: "性格分析"
     *         },
     *         {
     *         id: "3",
     *         name: "学习压力"
     *         },
     *         {
     *         id: "4",
     *         name: "人际沟通"
     *         },
     *         {
     *         id: "5",
     *         name: "情绪问题"
     *         }  
     *  }
     *  ]
     *  }
     */
    public function typeList()
    {
        $experts_api = config('experts_api');
        $url = $experts_api . '/api/user/typeList/';
        $data = curl_api($url, '', 'post');
        echo json_encode($data);
    }
}
