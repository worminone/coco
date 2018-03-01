<?php
namespace app\message\controller;

use app\common\controller\Admin;
use think\Request;

class Index extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get} /message/index/getList 系统消息列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGroup                MESSAGE
     * @apiDescription          所有系统消息列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          type 消息类型（可选）,1:院校入驻审核,2:高中入驻审核,3:招商加盟,4:大学信息审核,
     * @apiParam {Int}          status 1:已查看，0：未查看
     * @apiParam {Int}          pagesize 每页的条目数（可选），默认为10
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     */
    public function getList()
    {
        $where = [];
        $status = input('get.status');
        if (key_exists('status', input('get.')) && $status !== '') {
            $where['status'] = $status;
        }

        $type= input('get.type', 0, 'intval');
        if ($type) {
            $where['type'] = $type;
        }

        $page = input('get.page', 1, 'intval');
        $pageSize = input('get.pagesize', config('pagesize'), 'intval');
        $model = db('message');

        $count = $model->where($where)->count();
        $list = $model->field('*')->where($where)->limit($pageSize)->page($page)->order('create_time desc')->select();
        $data = ['count'=>$count,'pagesize'=>config('pagesize'), 'data'=>$list];
        //         aa($list);
        $this->response('1', '获取成功', $data);
    }


    /**
     * @api {get} /message/index/view   查看系统消息详情
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGroup                MESSAGE
     * @apiDescription          查看系统消息详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 消息ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function view()
    {
        $id = input('get.id', 0, 'intval');
        if (! $id) {
            $this->response(-1, '消息ID不能为空');
        }

        $info = db('message')->find($id);
        if ($info['status'] == 0) {
            db('message')->where('id=' . $id)->update(['status'=>1]);
        }

        $this->response('1', '获取成功', $info);

    }

    /**
     * @api {post} /message/index/setRead   标记已读
     * @apiVersion              1.0.0
     * @apiName                 setRead
     * @apiGroup                MESSAGE
     * @apiDescription          把消息标记成已读状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          ids 消息ID，如果多个消息，ID用逗号分隔
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function setRead()
    {
        $ids = input('post.ids');
        if (! $ids) {
            $this->response(-1, '消息ID不能为空');
        }

        $idArr = explode(',', $ids);
        $where = [];
        $where['id'] = ['in', $idArr];
        db('message')->where($where)->update(['status'=>1]);

        $this->response('1', '操作成功');

    }

    /**
     * @api {get} /message/index/count   所有未读数量（高榕）
     * @apiVersion              1.0.0
     * @apiName                 count
     * @apiGroup                MESSAGE
     * @apiDescription          所有未读消息的数量
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function count()
    {
        $data = [];
        $where = [];
        $where['status'] = 0;
        $data['count'] = db('message')->where($where)->count();

        $this->response('1', '操作成功', $data);

    }

}