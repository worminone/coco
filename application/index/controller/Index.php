<?php
namespace app\index\controller;

class Index extends \think\Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index(){
        echo 'hello, ddzx, ddzx_admin_api!';
        exit;
    }

    //广告点击展示随机
    public function test()
    {

        $count = 50;
        for ($i=0; $i<=$count; $i++) {
            $data = [];
            $where = ['status'=>1, 'release_status'=>2];
            $data['ad_id'] = array_rand(db('ad')->where($where)->column('id'));
            $data['province_id'] = array_rand(get_region_list());
            $moth = sprintf("%02d", rand(8, 10));;
            $day = sprintf("%02d", rand(1,30));
            $data['the_date'] = "2017-{$moth}-{$day}";
            $data['view_count'] = rand(500,1000);
            $data['click_count'] = rand(50,300);

//             aa($data);
            db('ad_statistics')->insert($data);
        }


    }
}