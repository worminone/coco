<?php

namespace app\message\controller;

use think\Db;
use think\cache;
use think\Request;
use app\common\controller\Admin;

class Feedback extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /message/Feedback/getList 获取问题反馈列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                Feedback
     * @apiDescription          获取问题反馈列表
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
     *   id: 问题反馈ID,
     *   type: 问题类型,
     *   telphone: 联系方式,
     *   contact: 联系人,
     *   describe: 问题描述,
     *   status: 状态
     * }
     */
    public function getList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $type = input('param.type', '-1', 'int');
        $term_type = input('param.term_type', '3', 'int');
        $status = input('param.status', '-1', 'int');
        $where = '';
        if ($term_type > 0) {
            $where['term_type'] = $term_type;
        }
        if ($type > 0) {
            $where['type'] = $type;
        }
        if ($status >= 0) {
            $where['status'] = $status;
        }
        $list = $this->getPageList('Feedback', $where, 'id desc', '*', $pagesize);
        foreach ($list['list'] as $key => $value) {
            if($value['term_type'] == 4) {
                $info = model('Message')->getQuestionType();
            } else {
                $info = model('Message')->getFeedbackType();
            }
//            dd($info);
            $list['list'][$key]['type'] = $info[$value['type']];
        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /message/Feedback/editInfo 查看编辑问题反馈
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                Feedback
     * @apiDescription          查看编辑问题反馈
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    问题反馈ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 问题反馈ID,
     *   type: 问题类型,
     *   telphone: 联系方式,
     *   contact: 联系人,
     *   describe: 问题描述,
     *   status: 状态
     * }
     *
     */
    public function editInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $info = DB::name('Feedback')->where(['id'=>$id])->find();
        if($info['term_type'] == 4) {
            $type = model('Message')->getQuestionType();
        } else {
            $type = model('Message')->getFeedbackType();
        }
        $info['type'] = $type[$info['type']];
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }


    /**
     * @api {post} /message/Feedback/saveInfo 修改问题反馈
     * @apiVersion              1.0.0
     * @apiName                 saveInfo
     * @apiGROUP                Feedback
     * @apiDescription          修改问题反馈
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          id      问题反馈ID,
     * @apiParam {Int}          type    问题类型,
     * @apiParam {String}       telphone 联系方式,
     * @apiParam {String}       contact  联系人,
     * @apiParam {String}       describe 问题描述,
     * @apiParam {Int}          status 状态
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function saveInfo()
    {
        $param = input('param.');
        $user_info = $this->userInfo;
        $param['user_name'] = $user_info['true_name'];
        if (empty($param)) {
            $this->response('-1', '参数不能为空');
        }
        $res = DB::name('Feedback')->update($param);
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /message/Feedback/delInfo 修改问题反馈
     * @apiVersion              1.0.0
     * @apiName                 delInfo
     * @apiGROUP                Feedback
     * @apiDescription          修改问题反馈
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    问题反馈ID
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
        $res = DB::name('Feedback')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

    /**
     * @api {post} /message/Feedback/getType 获取问题类型
     * @apiVersion              1.0.0
     * @apiName                 getType
     * @apiGROUP                Feedback
     * @apiDescription          获取问题类型
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    问题反馈ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function getType(){
        $term_type = input('param.term_type');
        if( $term_type == 4) {
            $info = model('Message')->getSchoolFeedback();
        } else {
            $info = model('Message')->getFeedback();
        }

        $this->response('1', '获取成功', $info);
    }

}
