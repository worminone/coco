<?php
namespace app\lessonplan\model;

use think\Db;
use Think\Model;

class TchPrepares
{
    public function getPreLis($where, $limit)
    {
        $tchList = Db::name('teachingCatalogue')->where($where)->limit($limit)->select();
        return $tchList;
    }

    public function getPreLisTotal($where)
    {
        $total = Db::name('teaching_catalogue')->where($where)->count();
        return $total;
    }

    public function getLesson($id)
    {
        $class = Db::name('prepare_lesson')->where('catalogue_id',$id)->field('description,id,cover')->find();
        return $class;
    }
}
