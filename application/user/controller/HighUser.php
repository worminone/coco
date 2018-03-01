<?php
namespace app\user\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class HighUser extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    public function highUserList()
    {
        // $major_name = input('param.majorName', '', 'htmlspecialchars');
        $college_api = config('base_api');
        $url =  $college_api.'/api/Member/memberList';
        $param['utype'] = 99;
        $this->curl_api($url, $param, 'post');
    }
}

