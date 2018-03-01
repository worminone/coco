<?php
namespace app\auth\controller;

use app\common\controller\Admin;
use think\Request;
use think\Cache;
use think\Loader;
use think\Db;

class Role extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get} /auth/role/index 查看角色列表
     * @apiVersion              1.0.0
     * @apiName                 index
     * @apiGROUP                ROLE
     * @apiDescription          查看和搜索角色列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       name 角色名称（可选）
     * @apiParam {Int}          status 状态(可选）默认为1：正常，0：禁用
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 当前页数（可选）
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
        if (key_exists('status', input('get.'))) {
            $where['status'] = input('get.status');
        } else {
            $where['status'] = ['in', [1, 2]];
        }

        $pagesize = input('param.pagesize', config('pagesize'), 'intval');

        $name = input('get.name', '');
        if ($name) {
            unset($where['status']);
            $where['name'] = array('like','%'.$name.'%');
        }

        $page = input('get.page', 1, 'intval');
        $model = db('role');
        $count = $model->where($where)->count();
        $list = $model->where($where)->limit($pagesize)->page($page)->order('create_time desc')->select();
        $output = ['count'=>$count,'pagesize'=>$pagesize, 'data'=>$list];
        $this->response('1', '获取成功', $output);
    }

    /**
     * @api {get} /auth/role/view 查看单个角色信息
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGROUP                ROLE
     * @apiDescription          查看单个角色信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 角色ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function view()
    {
        $id = input('get.id');
        if (! $id) {
            $this->response('-1', '角色id不能为空');
        }

        $info = db('role')->find($id);
        $info['menu_id'] = array_map('intval', explode(',', $info['menu_id']));
        $info['user_role_id'] = db('admin_user')->where("role_id like '%,".$info['id'].",%'")->column('id');
//         aa($userInfo);
        $this->response('1', '获取成功', $info);
    }

    /**
     * @api {post} /auth/role/add 添加角色信息
     * @apiVersion              1.0.0
     * @apiName                 add
     * @apiGROUP                ROLE
     * @apiDescription          添加角色信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       name 角色名称
     * @apiParam {String}       menu_id 菜单ID，用逗号隔开，比如(2,4,12,18,22)
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
            $this->response(-10410, '请输入角色名称');
        }

        $where = "(name='$name')";
        $count = db('role')->where($where)->count();
        if ($count > 0) {
            $this->response(-10412, '该角色已经存在');
        }

        $data = input('post.');
        $data['status'] = 1;
        unset($data['token']);

        $role_id = db('role')->insertGetId($data);
        if ($role_id) {
            $this->response(1, '角色添加成功', ['role_id'=>$role_id]);
        } else {
            $this->response(-10415, '角色添加失败');
        }
    }

    /**
     * @api {post} /auth/role/edit 编辑角色信息
     * @apiVersion              1.0.0
     * @apiName                 edit
     * @apiGROUP                ROLE
     * @apiDescription          角色信息的修改
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 角色的id
     * @apiParam {String}       name 角色名称
     * @apiParam {String}       menu_id 菜单ID，用逗号隔开，比如(2,4,12,18,22)
     * @apiParam {Int}          status 状态
     * @apiParam {String}       remark 备注说明（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function edit()
    {
        $id = input('id');
        $name = input('post.name');

        $info = db('role')->where('id='.$id)->find();
//         echo $userModel->user_name;exit;

        if (! $id) {
            $this->response(-10320, '请输入角色ID');
        }

        if (! $name) {
            $this->response(-10321, '请输入角色名称');
        }


        if ($info['name'] != $name) {
            $where = ['name' => $name];
            $count = db('role')->where($where)->count();
            if ($count > 0) {
                $this->response(-10322, '该角色已经存在');
            }
        }
        $data = input('post.');
        unset($data['token']);
        $bool = db('role')->where('id='.$id)->update($data);
        if ($bool !== false) {
            $this->response(1, '角色信息更新成功');
        } else {
            $this->response(-10323, '角色信息更新失败');
        }
    }


    /**
     * @api {post} /auth/role/delete 删除角色信息
     * @apiVersion              1.0.0
     * @apiName                 delete
     * @apiGROUP                ROLE
     * @apiDescription          删除角色信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 角色的id
     * @apiParam {String}       status 状态（0 删除 1恢复 2 冻结）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function delete()
    {
        $id = input('param.id');
        $status = input('param.status');
        $where = [];
        $where['id'] = ['in', $id];
        $info = db('role')->where($where)->find();

        if (! $info) {
            $this->response(-1, '角色ID不能为空');
        }

        //0的时候直接从数据库物理删除
        if ($status === '0') {
            $bool = Db::name('role')->where($where)->delete();
        } else {
            $bool = Db::name('role')->where($where)->update(['status'=>$status]);
        }


        if ($bool !== false) {
            $this->response(1, '角色信息更新成功');
        } else {
            $this->response(-10323, '角色信息更新失败');
        }
    }

    /**
     * @api {get} /auth/role/menuList 获取所有菜单节点数据
     * @apiVersion              1.0.0
     * @apiName                 menuList
     * @apiGROUP                ROLE
     * @apiDescription          获取所有菜单节点数据
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function menuList()
    {
        if (config('my_env') == 'dev') {
            $key = rand_string();
        } else {
            $key = 'menu_list';
        }

        $data = Cache::get($key);
        if (! $data) {
            $srcData = db('menu')->field('id, parent_id,name')->where('status=1')->select();
            $BuildTreeArray = new \BuildTreeArray($srcData, 'id', 'parent_id', 0);
            $data= $BuildTreeArray->getChildren(0);
            Cache::set($key, $data);
        }

        $this->response(1, '获取成功', $data);
    }



    /**
     * @api {post} /auth/role/userRole  给用户分配角色
     * @apiVersion              1.0.0
     * @apiName                 userRole
     * @apiGROUP                ROLE
     * @apiDescription          给用户分配角色
     * @apiParam {String}       ids 用户ID(2,4,12,18,22)
     * @apiParam {Int}          role_id 角色ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function userRole()
    {
        $ids = input('post.ids');
        $roleId = input('post.role_id');
        if (! $ids) {
            $this->response(-10328, '用户ID不能为空');
        }

        if (! $roleId) {
            $this->response(-10329, '角色ID不能为空');
        }

        $idArr = explode(',', $ids);
        foreach ($idArr as $one) {
            $userRoleId = db('admin_user')->where('id=' . $one)->value('role_id');
            $userRoleIdArr = explode(',', $userRoleId);
            if (! in_array($roleId, $userRoleIdArr)) {
                $userRoleIdArr[] =  $roleId;
                $userRoleId = implode(',', $userRoleIdArr);
                $userRoleId = trim($userRoleId, ',');
                $userRoleId = ',' . $userRoleId . ',';
                db('admin_user')->where('id='.$one)->update(['role_id'=>$userRoleId]);
            }
        }

        $this->response(1, '操作成功');
    }
}
