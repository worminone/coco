<?php

namespace app\system\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;
class SchoolPicture extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /system/SchoolPicture/getList 获取中学登录界面列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                SchoolPicture
     * @apiDescription          获取中学登录界面列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       school_name 中学名称
     * @apiParam {int}          page   当前页
     * @apiParam {int}          pagesize 当前页数
     *
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 中学登录界面ID,
     *   school_badge: 校徽,
     *   country: 省市区,
     *   school_name 学校名称
     * }
     */
    public function getList()
    {
        $school_name = input('param.school_name', '', 'htmlspecialchars');
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        if (!empty($school_name)) {
            $where['school_name'] = ['like', "%".$school_name."%"];
        }
        $list = $this->getPageList('SchoolPicture', $where, 'id desc', '*', $pagesize);
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getInfo';
        foreach ($list['list'] as $key => $value) {
            $param['school_id'] = $value['school_id'];
            $data = curl_api($url, $param, 'post');
            $list['list'][$key]['school_name'] = $data['data']['school_name'];
            $list['list'][$key]['country'] = $data['data']['sch_province'].' '.$data['data']['sch_city'].' '.$data['data']['region_name'];
        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/SchoolPicture/addInfo 新增中学登录界面
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                SchoolPicture
     * @apiDescription          新增中学登录界面
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          region_id       市区ID
     * @apiParam {String}       school_badge    校徽
     * @apiParam {String}       school_name     学校名
     * @apiParam {String}       school_img      轮播图图片(多个‘,’分割)
     * @apiParam {Int}          school_id       学校ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addInfo()
    {
        $info = input('param.');
        if (empty($info)) {
            $this->response('-1', '参数不能为空');
        }
        $verify =DB::name('SchoolPicture')->where(['school_id'=>$info['school_id']])->find();
        if ($verify) {
            $this->response('-1', '学校不能重复');
        }
        $res = DB::name('SchoolPicture')->insert($info);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /system/SchoolPicture/editInfo 查看编辑中学登录界面
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                SchoolPicture
     * @apiDescription          查看编辑中学登录界面
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    中学登录界面ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *
     *   id:            中学登录界面ID,
     *   school_badge:  校徽,
     *   school_name    学校名称
     *   school_img     轮播图图片(多个‘,’分割)
     *   school_id      学校ID
     *   province_id    省份ID
     *   city_id        城市ID
     * }
     *
     */

    public function editInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $info = DB::name('SchoolPicture')->where(['id'=>$id])->find();
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getInfo';
        $param['school_id'] = $info['school_id'];
        $data = curl_api($url, $param, 'post');
        $info['province_id'] = $data['data']['province_id'];
        $info['city_id'] = $info['region_id'];
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }


    /**
     * @api {post} /system/SchoolPicture/saveInfo 修改中学登录界面
     * @apiVersion              1.0.0
     * @apiName                 saveInfo
     * @apiGROUP                SchoolPicture
     * @apiDescription          修改中学登录界面
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          id              中学登录界面id
     * @apiParam {Int}          region_id       市区ID
     * @apiParam {String}       school_badge    校徽
     * @apiParam {String}       school_name     学校名
     * @apiParam {String}       school_img      轮播图图片(多个‘,’分割)
     * @apiParam {Int}          school_id       学校ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function saveInfo()
    {
        $info = input('param.');
        if (empty($info)) {
            $this->response('-1', '参数不能为空');
        }
        $res = DB::name('SchoolPicture')->update($info);
        $verify =DB::name('SchoolPicture')->where(['school_id'=>$info['school_id']])->find();
        if ($verify && ($verify['id'] != $info['id'])) {
            $this->response('-1', '网站标题不能重复');
        }
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/SchoolPicture/delInfo 删除中学登录界面
     * @apiVersion              1.0.0
     * @apiName                 delInfo
     * @apiGROUP                SchoolPicture
     * @apiDescription          修改中学登录界面
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    中学登录界面ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function delInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $where['id'] = ['in', $id];
        $res = DB::name('SchoolPicture')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

    /**
     * @api {post} /system/SchoolPicture/getExRegion 已入驻的省市
     * @apiVersion 1.0.0
     * @apiName getExRegion
     * @apiGroup SchoolPicture
     * @apiDescription 已入驻的省市
     *
     * @apiParam {String} token 用户的token.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     *  data: [
     *      {
     *      region_id: 省份ID,
     *      region_name: "省份名称",
     *      city: [
     *      {
     *          region_id: 城市ID,
     *          region_name: "城市名称"
     *      }
     *      ]
     *   },
     * }
     */
    public function getExRegion()
    {
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getExRegion';
        $param['status'] = 0;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /system/SchoolPicture/getSchoolList 获取学校名称
     * @apiVersion 1.0.0
     * @apiName getSchoolList
     * @apiGroup SchoolPicture
     * @apiDescription 获取学校名称
     *
     * @apiParam {String} token      用户的token.
     * @apiParam {Int}    city_id    区ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     *  data: [{
     *      id：学校ID
     *      sch_name: 学校名称,
     *      province_id: "省份ID"
     *      city_id :城市ID
     *   },
     * }
     */
    public function getSchoolList()
    {
        $city_id = input('param.city_id');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getSchoolList';
        $param['city_id'] = $city_id;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
