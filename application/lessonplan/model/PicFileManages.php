<?php
namespace app\lessonplan\model;

use think\Db;
use Think\Model;

class PicFileManages
{
    public function getPicFileList($where, $limit)
    {
        $PicFil = Db::name('admin_upload')->where($where)->limit($limit)->select();
        return $PicFil;
    }

    public function getPicFileLisTotal($where)
    {
        $total = Db::name('admin_upload')->where($where)->count();
        return $total;
    }

    public function deletePicFileLis($id)
    {
        Db::name('admin_upload')->where('id',$id)->delete();
        return '1';
    }
}
