<?php
namespace app\auth\model;

use think\Model;
use think\Cache;

class Member extends Model
{
    protected $table = 'dd_admin_user';
    protected $pk = 'id';
    protected $resultSetType = 'collection';


    public function __construct()
    {
        parent::__construct();
    }


    public function updateUserData($token, $data)
    {
        $userInfo = Cache::get($token);
        $where = [];
        $where['id'] = $userInfo['id'];

        $bool = $this->where($where)->update($data);
        return $bool;
    }


    //获取单个用户的资料
    public function getInfo($id)
    {
        $data = db('admin_user')->where('id='.$id)->find();
        if ($data['group_id']) {
            $data['group_name'] = db('group')->where('id='.$data['group_id'])->value('name');
        } else {
            $data['group_name'] = '';
        }
        $data['role_name'] = $this->getUserRoleName($id);
        return $data;
    }

    //获取管理员用户列表的资料
    public function getList($where, $page, $pageSize)
    {
        $field = 'id,job_num,user_name,true_name,group_id,email,status,remark,create_time,avatar,open_id,role_id';
        $model = db('admin_user');

        $count = $model->field($field)->where($where)->count();
        $list = $model->field($field)->where($where)->limit($pageSize)->page($page)->select();
        //aa($list);

        foreach ($list as &$one) {
            //获取角色组的名称
            $roleName = '';
            if ($one['role_id']) {
                $roleIdArr = explode(',', $one['role_id']);
                foreach ($roleIdArr as $roleId) {
                    $theRoleName = '';
                    if ($roleId) {
                        $theRoleName = db('role')->where('id='.$roleId. ' AND status=1')->value('name');
                    }

                    if ($theRoleName) {
                        $roleName .=  ',' . $theRoleName;
                    }

                }
                $one['role_name'] = substr($roleName, 1);
            } else {
                $one['role_name'] = '';
            }


            if ($one['group_id']) {
                $one['group_name'] = db('group')->where('id='.$one['group_id'])->value('name');
            } else {
                $one['group_name'] = '';
            }

            if($one['open_id'] != '') {
                $one['wx_status'] = 1;
            } else {
                $one['wx_status'] = 0;
            }


        }

        return ['count'=>$count,'pagesize'=>config('pagesize'), 'data'=>$list];
    }

    //登录成功处理的逻辑
    public function loginSuccess($user)
    {

        $token = make_token($user['id']);
        // 默认永远不超时，除非用户自己退出
        $data = [];
        $data['token'] = $token;
        $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();

        db('admin_user')->where('id =' .$user['id'])->update($data);

        //记录登录时间到缓存
        $lastActionTimeKey = 'last_action_time:' . $user['id'];
        Cache::set($token, $user);
        Cache::set($lastActionTimeKey, time());

        return $token;
    }

    //获取用户的角色名称
    public function getUserRoleName($uid)
    {
        $roleId = db('admin_user')->where('id='.$uid)->value('role_id');
        $name = '';
        if (! $roleId) {
            return $name;
        }

        $roleIdArr = explode(',', $roleId);
        foreach ($roleIdArr as $one) {
            if ($one) {
                $name .= ',' . $this->getRoleName($one);
            }

        }

        $name = substr($name, 1);
        return $name;

    }


    //获取角色名称
    public function getRoleName($roleId)
    {
        $roleName = db('role')->where('id='. $roleId)->value('name');

        return $roleName;
    }
}
