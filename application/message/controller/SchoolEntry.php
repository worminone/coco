<?php

namespace app\Message\controller;

use think\Db;
use \app\system\model\Message;
use think\Request;
use app\common\controller\Admin;
class SchoolEntry extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /message/SchoolEntry/getList 获取学校入驻列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                SchoolEntry
     * @apiDescription          获取学校入驻列表
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
     *   id: 1,
     *   contact: 联系人,
     *   telphone: 联系手机,
     *   term_type: 渠道来源(1.校园在线官网2 app 3 一体机),
     *   mumber: 合作编号,
     *   user_name: 用户名,
     *   status: 状态（0 未读 1 已读）,
     * }
     */
    public function getList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $status = input('param.status', '-1', 'int');
        $where = '';
        if($status >= 0) {
            $where['status'] = $status;
        }
        $list = $this->getPageList('SchoolEntry', $where, 'id desc', '*', $pagesize);
        $info = model('Message')->getTermType();
        foreach ($list['list'] as $key => $value) {
            if($value['term_type'] != '') {
                $list['list'][$key]['term_type'] = $info[$value['term_type']];
            }
            if ($value['id'] < 10 ) {
                $id = '0'.$value['id'];
            } else {
                $id = $value['id'];
            }
            $num = date('Ymd',strtotime($value['create_time'])).$id;
            $list['list'][$key]['number'] = $num;

        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /message/SchoolEntry/editInfo 查看编辑学校入驻已读
     * @apiVersion              1.0.0
     * @apiName                 editInfo
     * @apiGROUP                SchoolEntry
     * @apiDescription          查看编辑学校入驻已读
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
     *   id: 1,
     *   contact: 联系人,
     *   telphone: 联系手机,
     *   term_type: 渠道来源(1.校园在线官网2 app 3 一体机),
     *   mumber: 合作编号,
     *   user_name: 用户名,
     *   status: 状态（0 未读 1 已读）,
     * }
     */
    public function editInfo()
    {
        $id = input('param.id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $info = DB::name('SchoolEntry')->where(['id'=>$id])->find();
        $type = model('Message')->getTermType();
        $info['term_type'] = $type[$info['term_type']];
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据', $info);
        }
    }

    /**
     * @api {post} /message/SchoolEntry/setInfo 修改学校入驻已读
     * @apiVersion              1.0.0
     * @apiName                 setInfo
     * @apiGROUP                SchoolEntry
     * @apiDescription          修改学校入驻已读
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    学校入驻ID
     * @apiParam {Int}          user_name    操作人
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function setInfo()
    {
        $id = input('param.id');
        $user_name = input('param.user_name');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $where['id'] = ['in', $id];
        $data['status'] = 1;
        $data['user_name'] = $user_name;
        $info = DB::name('SchoolEntry')->where($where)->update($data);
        if ($info) {
            $this->response('1', '修改成功');
        } else {
            $this->response('-1', '修改失败');
        }
    }


}
