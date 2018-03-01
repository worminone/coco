<?php

namespace app\user\model;

use think\Db;
use app\common\controller\Base;

class AdminUser
{
    /**
     * 根据用户id查询管理员名
     * @param $id
     * @return mixed
     */
    public function getNameById($id)
    {
        $name = DB::name('AdminUser')->where(['id' => $id])->value('true_name');
        return !empty($name) ? $name : '';
    }
}
