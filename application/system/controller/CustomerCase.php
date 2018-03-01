<?php

namespace app\system\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;
class CustomerCase extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /system/CustomerCase/getList 获取客户案例列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                CustomerCase
     * @apiDescription          获取客户案例列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          page   当前页
     * @apiParam {int}          pagesize 当前页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 客户案例ID,
     *   school_name: 学校名称,
     *   school_motto: 校训,
     *   school_pic: 学校图片,
     * }
     */
    public function getList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('CustomerCase', $where, 'id desc', '*', $pagesize);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/CustomerCase/addInfo 新增客户案例
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                CustomerCase
     * @apiDescription          新增客户案例
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       school_name   学校名称
     * @apiParam {String}       school_motto 网站网址
     * @apiParam {String}       school_pic   学校图片
     * @apiParam {Int}          sort   排序
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

        $verify =DB::name('CustomerCase')->where(['school_name'=>$info['school_name']])->find();
        if ($verify) {
            $this->response('-1', '标题不能重复');
        }
        $res = DB::name('CustomerCase')->insert($info);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/CustomerCase/editInfo 查看编辑客户案例
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                CustomerCase
     * @apiDescription          查看编辑客户案例
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    客户案例ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 客户案例ID,
     *   school_name: 学校名称,
     *   school_motto: 校训,
     *   school_pic: 学校图片,
     * }
     *
     */
    public function editInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $info = DB::name('CustomerCase')->where(['id'=>$id])->find();
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }


    /**
     * @api {post} /system/CustomerCase/saveInfo 修改客户案例
     * @apiVersion              1.0.0
     * @apiName                 saveInfo
     * @apiGROUP                CustomerCase
     * @apiDescription          修改客户案例
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       id   客户案例ID
     * @apiParam {String}       school_name   学校名称
     * @apiParam {String}       school_motto 网站网址
     * @apiParam {String}       school_pic   学校图片
     * @apiParam {Int}          sort   排序
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
        $verify =DB::name('CustomerCase')->where(['school_name'=>$info['school_name']])->find();
        if ($verify && ($verify['id'] != $info['id'])) {
            $this->response('-1', '网站标题不能重复');
        }

        $res = DB::name('CustomerCase')->update($info);
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/CustomerCase/delInfo 删除客户案例
     * @apiVersion              1.0.0
     * @apiName                 delInfo
     * @apiGROUP                CustomerCase
     * @apiDescription          删除客户案例
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    客户案例ID
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
        $res = DB::name('CustomerCase')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

}
