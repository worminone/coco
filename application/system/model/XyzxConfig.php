<?php

namespace app\system\model;

use think\Db;
use app\common\controller\Base;

class XyzxConfig
{
    public function getInfo()
    {
        $data = DB::name('xyzx_config')->find();
        return $data;
    }

    public function getHomeCoveList($where)
    {
    	$data = DB::name('HomeCover')
	    	->where($where)
	    	->select();
        foreach ($data as $key => $value) {
            if($value['id'] == 21) {
                $data[$key]['img_url'] = '';
            }
        }
	    return $data;
    }

}
