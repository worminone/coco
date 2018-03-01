<?php

namespace app\system\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;
class Partner extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /system/Partner/getList 获取合作伙伴列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                Partner
     * @apiDescription          获取合作伙伴列表
     * @apiParam {String}       token 已登录账号的token
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
     *   id: 合作伙伴ID,
     *   title: 合作伙伴名称,
     *   website: 网址,
     * }
     */
    public function getList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('Partner', $where, 'id desc', '*', $pagesize);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/Partner/addInfo 新增合作伙伴
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                Partner
     * @apiDescription          新增合作伙伴
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       title   网站标题
     * @apiParam {String}       website 网站网址
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
        $verify =DB::name('Partner')->where(['title'=>$info['title']])->find();
        if ($verify) {
            $this->response('-1', '标题不能重复');
        }
        $res = DB::name('Partner')->insert($info);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/Partner/editInfo 查看编辑合作伙伴
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                Partner
     * @apiDescription          查看编辑合作伙伴
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    合作伙伴ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 1,
     *   title: 合作伙伴名称,
     *   website: 网址,
     *   sort ：排序
     * }
     *
     */
    public function editInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $info = DB::name('Partner')->where(['id'=>$id])->find();
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }


    /**
     * @api {post} /system/Partner/saveInfo 修改合作伙伴
     * @apiVersion              1.0.0
     * @apiName                 saveInfo
     * @apiGROUP                Partner
     * @apiDescription          修改合作伙伴
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          id   合作伙伴id
     * @apiParam {String}       title   网站标
     * @apiParam {String}       website 网站网址
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
        $verify =DB::name('Partner')->where(['title'=>$info['title']])->find();
        if ($verify && ($verify['id'] != $info['id'])) {
            $this->response('-1', '网站标题不能重复');
        }
        $res = DB::name('Partner')->update($info);
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/Partner/delInfo 删除合作伙伴
     * @apiVersion              1.0.0
     * @apiName                 delInfo
     * @apiGROUP                Partner
     * @apiDescription          修改合作伙伴
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    合作伙伴ID
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
        $res = DB::name('Partner')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

}
