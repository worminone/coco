<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace tests;

use think\Cache;

class AuthTest extends TestCase
{

    //管理员添加
    public function testUserAdd()
    {
        $api = $this->baseUrl . '/auth/user/add';
        $para = [];

        unset($para);
        $para['token'] = Cache::get('token');
        $para['user_name'] = '189' . rand(0000, 9999) . '9999';
        $para['password'] = '123456';
        $para['true_name'] = rand_string();
        $para['group_id'] = rand(1,7);
        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

     //管理员编辑
    public function testUseredit()
    {
        $api = $this->baseUrl . '/auth/user/edit';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['id'] = 3;
        $para['user_name'] = '189' . rand(0000, 9999) . '9999';
        $para['password'] = rand_string();
        $para['true_name'] = rand_string();
        $para['group_id'] = rand(1,6);
        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //管理员信息查看
    public function testUserView()
    {
        sleep(3);
        $api = $this->baseUrl . '/auth/user/view';
        $para = [];

        $para['token'] = Cache::get('token');
        //aa(Cache::get('token'));
        $para['id'] = 3;

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //管理员信息列表
    public function testUserList()
    {
        $api = $this->baseUrl . '/auth/user/index';
        $para = [];

        $para['token'] = Cache::get('token');
        //         aa(Cache::get('token'));

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //部门列表
    public function testGroupList()
    {
        $api = $this->baseUrl . '/auth/group/index';
        $para = [];

        $para['token'] = Cache::get('token');

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //单个部门信息查看
    public function testGroupView()
    {
        $api = $this->baseUrl . '/auth/group/index';
        $para = [];

        $para['token'] = Cache::get('token');

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //添加部门
    public function testGroupAdd()
    {
        $api = $this->baseUrl . '/auth/group/add';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['name'] = rand_string();
        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //编辑部门
    public function testGroupedit()
    {
        $api = $this->baseUrl . '/auth/group/edit';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['id'] = 3;
        $para['name'] = '马革裹尸';

        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }







    //添加角色
    public function testRoleAdd()
    {
        $api = $this->baseUrl . '/auth/role/add';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['name'] = rand_string();
        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }


    //角色列表
    public function testRoleList()
    {
        $api = $this->baseUrl . '/auth/role/index';
        $para = [];

        $para['token'] = Cache::get('token');

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //单个角色信息查看
    public function testRoleView()
    {
        $api = $this->baseUrl . '/auth/role/index';
        $para = [];

        $para['token'] = Cache::get('token');

        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }



    //编辑角色
    public function testRoleedit()
    {
        $api = $this->baseUrl . '/auth/role/edit';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['id'] = 1;
        $para['name'] = '马革裹尸';
        $para['status'] = rand(0, 9);

        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

    //给多个用户分配角色
    public function testUserRole()
    {
        $api = $this->baseUrl . '/auth/role/userRole';
        $para = [];

        $para['token'] = Cache::get('token');
        $para['ids'] = '2,3,4';
        $para['role_id'] = 7;

        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }

}
