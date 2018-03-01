<?php
namespace app\auth\controller;

use app\common\controller\Admin;
use think\Request;

class Group extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get} /auth/group/index 查看部门列表
     * @apiVersion              1.0.0
     * @apiName                 index
     * @apiGROUP                GROUP
     * @apiDescription          后台管理员账号的添加
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       name 部门名称（可选）
     * @apiParam {Int}          status 状态(可选）默认为1：正常，0：禁用
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     */
    public function index()
    {
        $where = [];
        $status = input('get.status', 1, 'intval');
        if ($status) {
            $where['status'] = $status;
        }
        $name = input('get.name', '');
        if ($name) {
            $where['name'] = $name;
        }
        $page = input('get.page', 1, 'intval');
        $model = db('group')->where($where);
        $count = $model->count();
        $list = $model->limit(config('pagesize'))->page($page)->select();
        foreach ($list as $key => $value) {
            $list[$key]['id'] = (string)$value['id'];
        }
        $output = ['count'=>$count,'pagesize'=>config('pagesize'), 'data'=>$list];
//         aa($userInfo);
        $this->response('1', '获取成功', $output);
    }

    /**
     * @api {get} /auth/group/view 查看单个部门信息
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGROUP                GROUP
     * @apiDescription          查看管理员信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 部门ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function view()
    {
        $groupId = input('get.id');
        if (! $groupId) {
            $this->response('-1', 'group_id不能为空');
        }

        $Info = db('group')->find($groupId);
//         aa($userInfo);
        $this->response('1', '获取成功', $Info);
    }

    /**
     * @api {post} /auth/group/add 添加部门
     * @apiVersion              1.0.0
     * @apiName                 add
     * @apiGROUP                GROUP
     * @apiDescription          部门的添加
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       name 部门名称
     * @apiParam {Int}          pid 上次部门ID，默认是0
     * @apiParam {String}       remark 备注说明（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function add()
    {
        $name = input('post.name');

        if (! $name) {
            $this->response(-10310, '请输入部门名称');
        }

        $where = "(name='$name')";
        $count = db('group')->where($where)->count();
        if ($count > 0) {
            $this->response(-10312, '该部门已经存在');
        }

        $data = input('post.');
        $data['status'] = 1;
        unset($data['token']);

        $group_id = db('group')->insertGetId($data);
        if ($group_id) {
            $this->response(1, '部门添加成功');
        } else {
            $this->response(-10315, '部门添加失败');
        }
    }

    /**
     * @api {post} /auth/group/edit 编辑部门信息
     * @apiVersion              1.0.0
     * @apiName                 edit
     * @apiGROUP                GROUP
     * @apiDescription          部门信息的修改
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 部门的id
     * @apiParam {String}       name 部门名称
     * @apiParam {Int}          pid 上次部门ID，默认是0
     * @apiParam {String}       remark 备注说明（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function edit()
    {
        $groupId = input('id');
        $name = input('post.name');

        $info = db('group')->where('id='.$groupId)->find();
//         echo $userModel->user_name;exit;

        if (! $groupId) {
            $this->response(-10320, '请输入部门ID');
        }

        if (! $name) {
            $this->response(-10321, '请输入部门名称');
        }


        if ($info['name'] != $name) {
            $where = ['name' => $name];
            $count = db('group')->where($where)->count();
            if ($count > 0) {
                $this->response(-10322, '该部门已经存在');
            }
        }
        $data = input('post.');
        $data['update_time'] = time();
        unset($data['token']);
        $bool = db('group')->where('id='.$groupId)->update($data);
        if ($bool !== false) {
            $this->response(1, '部门信息更新成功');
        } else {
            $this->response(-10323, '部门信息更新失败');
        }
    }
}
