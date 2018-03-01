<?php
namespace app\auth\controller;

use app\common\controller\Admin;
use app\auth\model\Member;
use think\Request;
use think\Model;
use think\Db;

class User extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get} /auth/user/index 后台管理员列表
     * @apiVersion              1.0.0
     * @apiName                 index
     * @apiGroup                ADMINUSER
     * @apiDescription          管理员列表搜索查询
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       user_name 管理员登录名（可选）
     * @apiParam {String}       keyword 搜索关键字（可选）
     * @apiParam {Int}          status 状态（可选）默认为1：正常，0：禁用
     * @apiParam {Int}          group_id 管理员所在部门（可选）
     * @apiParam {Int}          pagesize 每页的条目数（可选），默认为10
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Bool}         show_admin 是否显示管理员: 1:显示，0：不显示
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     */
    public function index()
    {
        $userModel = new Member();
        $where = [];
        //列表页删除的不显示出来
        if (! key_exists('status', input('get.')) || input('get.status') === '') {
            $where['status'] = ['in',[1,2]];
        } else {
            $where['status'] = input('get.status');
        }
        $group_id = input('get.group_id', 0, 'intval');
        if ($group_id) {
            $where['group_id'] = $group_id;
        }

        $user_name = input('get.user_name', 0);
        if ($user_name) {
            $where['user_name'] = $user_name;
        }

        $showAdmin = input('get.show_admin', 1, 'intval');
        if ($showAdmin == 0 ) {
            $where['id'] = array('neq', 1);
        }

        $keyword = input('get.keyword', '');
        if ($keyword) {
            //关键字查询的时候忽略用户状态
            unset($where['status']);
            $where['user_name|true_name'] = array('like','%'.$keyword.'%');
        }

        $page = input('get.page', 1, 'intval');
        $pageSize = input('get.pagesize', config('pagesize'), 'intval');
        $userList = $userModel->getList($where, $page, $pageSize);
//         aa($userInfo);
        $this->response('1', '获取成功', $userList);
    }

    /**
     * @api {get} /auth/user/view 查看单个管理员信息
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGroup                ADMINUSER
     * @apiDescription          查看单个管理员信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 管理员ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function view()
    {
        $id = input('get.id');
        if (! $id) {
            $this->response('-1', 'id不能为空');
        }

        $userModel = new Member();
        $userInfo = $userModel->getInfo($id);
//         aa($userInfo);
        $this->response('1', '获取成功', $userInfo);
    }

    /**
     * @api {post} /auth/user/add 添加管理员（高榕）
     * @apiVersion              1.0.0
     * @apiName                 add
     * @apiGroup                ADMINUSER
     * @apiDescription          后台管理员账号的添加
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       user_name 管理员登录名(手机)
     * @apiParam {String}       password 管理员登录名密码
     * @apiParam {String}       true_name 管理员真实姓名
     * @apiParam {String}       group_id 管理员所在部门
     * @apiParam {String}       role_id 管理员的角色组ID，可多个，用逗号分隔
     * @apiParam {String}       job_num 工号（可选）
     * @apiParam {String}       avatar 头像（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function add()
    {
        $userModel = new Member();

        $userName = input('post.user_name');
        $password = input('post.password');
        $groupId = input('post.group_id');


        if ($password && strlen($password) != 32) {
            $password = md5($password);
        }

        if (! $userName) {
            $this->response(-10010, '请输入登录名');
        }
        if (! $password) {
            $this->response(-10011, '请输入密码');
        }

        if (! $groupId) {
            $this->response(-10112, '请选择部门');
        }


        if (! test_mobile($userName)) {
            $this->response(-10013, '手机号码格式不正确');
        }

        $where = "(user_name='$userName')";
        $count = $userModel->where($where)->count();
        if ($count > 0) {
            $this->response(-10012, '该手机已经被注册');
        }

        $jobNum = input('post.job_num');
        if ($jobNum != '') {
            $where = "job_num='$jobNum'";
            $count = $userModel->where($where)->count();
            if ($count > 0) {
                $this->response(-10016, '该用户的工号已被使用');
            }
        }


        $data = input('post.');
        $data['password'] = md5($data['password']);

        $userModel->save($data);
        $uid = $userModel->id;
        if ($uid) {
            $this->response(1, '管理员添加成功');
        } else {
            $this->response(-10015, '管理员添加失败');
        }
    }

    /**
     * @api {post} /auth/user/edit 编辑管理员
     * @apiVersion              1.0.0
     * @apiName                 edit
     * @apiGroup                ADMINUSER
     * @apiDescription          后台管理员账号信息的修改
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 管理员的id
     * @apiParam {String}       user_name 管理员登录名(手机)
     * @apiParam {String}       password 管理员登录名密码（可选）
     * @apiParam {String}       true_name 管理员真实姓名
     * @apiParam {String}       group_id 管理员所在部门
     * @apiParam {String}       avatar 头像（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function edit()
    {
        $userId = input('id');
        $userName = input('user_name');
        $password = input('password');
        $groupId = input('group_id');

        $userModel = Member::get($userId);

        $userInfo = db('admin_user')->where('id='.$userId)->find();
//         echo $userModel->user_name;exit;

        if (! $userName) {
            $this->response(-10110, '请输入登录名');
        }

        if (! $groupId) {
            $this->response(-10112, '请选择部门');
        }

        if (! test_mobile($userName)) {
            $this->response(-10113, '手机号码格式不正确');
        }

        if ($userInfo['user_name'] != $userName) {
            $where = ['user_name' => $userName];
            $count = $userModel->where($where)->count();
            if ($count > 0) {
                $this->response(-10114, '该手机已经被注册');
            }
        }

        $jobNum = input('post.job_num');
        if ($jobNum != '') {
            $where = "job_num='$jobNum' AND id !=$userId";
            $count = $userModel->where($where)->count();
            if ($count > 0) {
                $this->response(-10016, '该用户的工号已被使用');
            }
        }


        $data = input('post.');
        if ($password !== '') {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }

        $bool = $userModel->save($data, ['id'=>$userId]);
        if ($bool !== false) {
            $this->response(1, '管理员信息更新成功');
        } else {
            $this->response(-10115, '管理员信息更新失败');
        }
    }

    /**
     * @api {post} /auth/user/updatePassword 用户修改自己密码
     * @apiVersion 1.0.0
     * @apiName updatePassword
     * @apiGroup ADMINUSER
     * @apiDescription 用户修改自己密码
     * @apiParam {String} token 后台登录用户token(必填).
     * @apiParam {String} old_password 旧密码(必填).
     * @apiParam {String} password 用户密码6-12位字符(必填).
     * @apiParam {String} re_password 再次输入密码(必填).
     *
     * @apiSuccess {Int} code 错误代码，1是成功,<br>
     * -40009:token不能为空,<br>
     * -40010:前后两次输入的密码不匹配,<br>
     * -40011:新旧密码不能一样,<br>
     * -40012:旧密码不正确,<br>
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function updatePassword()
    {
        $token = input('post.token');
        $oldPassword = input('post.old_password');
        $password = input('post.password');
        $rePassword = input('post.re_password');

        if (!$token) {
            $this->response(-40009, 'token不能为空');
        }

        if ($rePassword != $password) {
            $this->response(-40010, '前后两次输入的密码不匹配');
        }

        if ($oldPassword == $password) {
            $this->response(-40011, '新旧密码不能一样');
        }

        $model = new Member();
        $where = [];
        $where['id'] = $this->uid;
        $where['password'] = md5($oldPassword);

        $save = [];
        $save['password'] = md5($password);
        $bool = $model->where($where)->update($save);

        if ($bool != false) {
            $this->response(1, '修改成功');
        } else {
            $this->response(-40012, '旧密码不正确');
        }
    }

    /**
     * @api {post} /auth/user/editAvatar 编辑头像
     * @apiVersion              1.0.0
     * @apiName                 editAvatar
     * @apiGroup                ADMINUSER
     * @apiDescription          编辑头像
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 被管理员的id
     * @apiParam {String}       avatar 头像
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function editAvatar()
    {
        $userId = input('id');
        $avatar = input('avatar');
        $data = ['id'=>$userId,'avatar'=>$avatar];
        $bool = DB::name('AdminUser')->update($data);
        if ($bool !== false) {
            $this->response(1, '头像更新成功');
        } else {
            $this->response(-1, '头像更新失败');
        }

    }

    /**
     * @api {post} /auth/user/delete 删除用户信息
     * @apiVersion              1.0.0
     * @apiName                 edit
     * @apiGROUP                ADMINUSER
     * @apiDescription          删除用户信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 角色的id
     * @apiParam {String}       status 用户状态 0：删除（不可见）； 1：正常 ；2：冻结
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function delete()
    {
        $id = input('post.id');
        $ids = explode(',', $id);
        //不能删除id为1的超级管理员
        if (in_array(1, $ids)) {
            $this->response(-1, '不能删除超级管理员');
        }

        $status = input('post.status');
        $where = [];
        $where['id'] = ['in', $id];

        if ($id == '' || ! is_array($ids) ) {
            $this->response(-1, '角色ID不能为空');
        }

        $bool = Db::name('AdminUser')->where($where)->update(['status'=>$status]);
        if ($bool !== false) {
            $this->response(1, '用户信息更新成功');
        } else {
            $this->response(-10323, '用户信息更新失败');
        }
    }

    /**
     * @api {get} /auth/user/logout 管理员用户退出登录
     * @apiVersion              1.0.0
     * @apiName                 logout
     * @apiGROUP                ADMINUSER
     * @apiDescription          管理员用户退出登录（高榕）
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function logout()
    {
        $bool = db('admin_user')->where('id=' . $this->uid)->update(['token'=>'']);
        if ($bool !== false) {
            $this->response(1, '退出成功');
        } else {
            $this->response(-1, '退出失败');
        }
    }
}
