<?php

namespace app\lessonplan\model;

use Think\Db;
use think\Model;

class AdminUpload extends Model
{
    protected $pk = 'id';

    /**
     * 查询资源列表
     * @param $where
     * @param string $order
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where, $order = 'id DESC', $limit = "0,10")
    {
        $data = array();
        $where['status'] = 1;
        $data['total'] = Db::name('admin_upload')->where($where)->count();
        $data['list'] = Db::name('admin_upload')->field('id,url,thumb,file_name,file_type,ext')->where($where)->order($order)->limit($limit)->select();
        return $data;
    }

    /**
     * 查询资源详情
     * @param int $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getInfoById($id)
    {
        $data = Db::name('admin_upload')->where(['id' => $id])->find();
        return $data;
    }
}
