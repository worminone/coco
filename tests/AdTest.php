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

class AdTest extends TestCase
{

    //管理员添加
    public function testAdAdd()
    {
        $api = $this->baseUrl . '/ad/index/adAdd';
        $para = [];

        unset($para);
        $para['token'] = Cache::get('token');
        $para['show_type'] = rand(1, 2);
        $para['advertiser_id'] = rand(1, 200);
        $para['post_id'] = rand(1, 200);
        $para['start_date'] = random_date('2017-10-03', '2017-11-03');
        $para['end_date'] = random_date($para['start_date'], '2017-12-03');

        $rand = rand(1,10);
        $allProvince = get_region_list();

        $provinceArr = array_rand($allProvince,$rand);
//         aa($provinceArr);

        $isCountry = rand(0, 1);
        if ($isCountry == 1) {
            $provinceStr = implode(',', $provinceArr);
            $para['province'] = $provinceStr;
        } else {
            $para['province'] =  0;
        }

        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }


    //管理员添加
    public function testSetStatus()
    {
        $api = $this->baseUrl . '/ad/index/setStatus';
        $para = [];

        unset($para);
        $para['token'] = Cache::get('token');
        $para['status'] = rand(0, 1);
        $adIds = db('ad')->column('id');
        $para['id'] = array_rand($adIds);

//         aa($adIds);
        $data = curl_api($api, $para, 'get', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }


    //查看时间排期
    public function fromTime()
    {
        $api = $this->baseUrl . '/ad/index/fromTime';
        $para = [];

        unset($para);
        $para['token'] = Cache::get('token');
        $para['show_type'] = 1;
        $para['start_date'] = '2017-09-03';
        $para['end_date'] = '2017-12-03';

        $rand = rand(1,10);
        $allProvince = get_region_list();

        $provinceArr = array_rand($allProvince,$rand);
        //         aa($provinceArr);

        $isCountry = rand(0, 1);
        if ($isCountry == 1) {
            $provinceStr = implode(',', $provinceArr);
            $para['province'] = $provinceStr;
        } else {
            $para['province'] =  0;
        }

        $data = curl_api($api, $para, 'post', 0);

        $successCode = '1';
        $this->assertEquals($successCode, $data['code']);
    }
}
