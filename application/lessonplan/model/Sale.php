<?php

namespace app\lessonplan\model;

use think\Db;
use \app\lessonplan\model\Menu;

class Sale
{
    public function getSaleCount($where)
    {

        $total = Db::name('teaching_sale')->alias('a')
            ->join("dd_teaching_combo b",'a.combo_id=b.id')
            ->where($where)->count();
        return $total;
    }
    public function getSaleData($where, $limit)
    {
        $list = Db::name('teaching_sale')->alias('a')
            ->join("dd_teaching_combo b",'a.combo_id=b.id')
            ->field("a.*,b.description")
            ->where($where)->limit($limit)->order('a.buy_time desc')->select();
        return $list;
    }
    public function getSaleDetail($where)
    {
        $data = Db::name('teaching_sale')->where($where)->find();
        return $data;
    }
    public function haveSale($school_id)
    {
        $where['school_id'] = $school_id;
        $flag = Db::name('teaching_sale')->where($where)->value('id');
        return $flag;
    }


}
