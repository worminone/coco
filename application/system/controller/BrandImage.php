<?php

namespace app\system\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;
class BrandImage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /system/BrandImage/getList 获取品牌形象列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                BrandImage
     * @apiDescription          获取品牌形象列表
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
     *   id: 品牌形象ID,
     *   describe: 文字说明,
     *   img: 缩略图,
     * }
     */
    public function getList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('BrandImage', $where, 'id desc', '*', $pagesize);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /system/BrandImage/addInfo 新增品牌形象
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                BrandImage
     * @apiDescription          新增品牌形象
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       describe 说明
     * @apiParam {String}       img   缩略图
     * @apiParam {Int}          sort  排序
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
        $res = DB::name('BrandImage')->insert($info);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/BrandImage/editInfo 查看编辑品牌形象
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                BrandImage
     * @apiDescription          查看编辑品牌形象
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    品牌形象ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 品牌形象id,
     *   describe: 文字说明,
     *   img: 缩略图,
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
        $info = DB::name('BrandImage')->where(['id'=>$id])->find();
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }


    /**
     * @api {post} /system/BrandImage/saveInfo 修改品牌形象
     * @apiVersion              1.0.0
     * @apiName                 saveInfo
     * @apiGROUP                BrandImage
     * @apiDescription          修改品牌形象
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {ID}           id   品牌形象id
     * @apiParam {String}       title   网站标
     * @apiParam {String}       describe 说明
     * @apiParam {String}       img   缩略图
     * @apiParam {Int}          sort  排序
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

        $res = DB::name('BrandImage')->update($info);
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /system/BrandImage/delInfo 删除品牌形象
     * @apiVersion              1.0.0
     * @apiName                 delInfo
     * @apiGROUP                BrandImage
     * @apiDescription          删除品牌形象
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    品牌形象ID
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
        $res = DB::name('BrandImage')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

}
