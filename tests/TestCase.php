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
use think\Log;

class TestCase extends \think\testing\TestCase
{

    protected $baseUrl = 'http://localhost';

    public function __construct()
    {
        $myHost = config('my_host');
        $token = Cache::get('token');
        echo "======== " . $myHost . "======token:" . $token . "=======\n";
        $this->baseUrl = 'http://ddzx.api.' . $myHost;

        $this->assertEquals('http://ddzx.api.gaorong.net', $this->baseUrl);



    }


    public function testLogin()
    {
        $api = $this->baseUrl . '/auth/login/login';
        $para = [];
        //$para['user_name'] = '189' . rand(0000, 9999) . '9999';
        $para['user_name'] = '18099999999';
        $para['password'] = '123456';
        $data = curl_api($api, $para, 'post', 0);
        $successCode = '1';
        if ($data['code'] == 1) {
            $key = 'token';
            Log::record('TEST', $data['data']['token']);
            Cache::set($key, $data['data']['token']);
        }


        $this->assertEquals($successCode, $data['code']);
    }
}
