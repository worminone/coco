<?php
namespace app\school\model;

use think\Model;
use think\Db;

class Common extends Model
{
    public function getPageList($table, $where, $order)
    {
        $page = input('param.page', '1', 'intval');
        $limit = config('paginate.list_rows');
        $count = DB::name($table)
                ->where($where)
                ->count();
        $page_num = ceil($count/$limit);
        $list = DB::name($table)
            ->where($where)
            ->limit($limit*($page-1), $limit)
            ->order($order)
            ->select();
        if (!empty($list)) {
            $data = [
                'count'   => $count,
                'page_num'=> $page_num,
                'list'    => $list
            ];
        } else {
            $data = [
                'count'   => 0,
                'page_num'=> $page_num,
                'list'    => []
            ];
        }
       
        return $data;
    }
}
